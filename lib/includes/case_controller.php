<?php
session_start();

require_once __DIR__ . '/../models/Defendant.php';
require_once __DIR__ . '/../models/Lawyer.php';
require_once __DIR__ . '/../models/CaseRecord.php';
require_once __DIR__ . '/../models/Charge.php';
require_once __DIR__ . '/../models/CourtEvent.php';

// Start wizard from /case/add
if ($action === 'add') {
    header("Location: " . BASE_URL . "/defendant/add");
    exit;
}

// Route to entity controllers for each step
if (strpos($action, 'add/') === 0) {
    $step = explode('/', $action)[1];

    switch ($step) {
        case 'defendant':
            require __DIR__ . '/defendant_controller.php';
            break;
        case 'charge':
            require __DIR__ . '/charge_controller.php';
            break;
        case 'lawyer':
            require __DIR__ . '/lawyer_controller.php';
            break;
        case 'event':
            require __DIR__ . '/event_controller.php'; 
            break;
        default: // deal with erroneous steps
            http_response_code(404);
            echo "Invalid step.";
    }

    return;
}

// to manage an existing case
if ($action === 'manage') {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("
        SELECT c.case_ID, d.Name AS defendant, l.Name AS lawyer
        FROM caserecord c
        JOIN defendant d ON c.defendant_ID = d.defendant_ID
        JOIN case_lawyer cl ON cl.case_ID = c.case_ID
        JOIN lawyer l ON cl.lawyer_ID = l.lawyer_ID
        ORDER BY c.case_ID DESC
    ");
    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ($app->render)('standard', 'case_manage', ['cases' => $cases]);
    return;
}

if ($action === 'edit' && isset($_GET['id'])) {
    $caseID = $_GET['id'];
    preload_case_into_session($caseID);  // next step below
    header("Location: " . BASE_URL . "/defendant/add");
    exit;
}

// handles review and confirmed case
if ($action === 'confirm') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_confirm_case($app);
    } else {
        show_review_page($app);
    }
    exit;
}

// show success message
if ($action === 'confirm_case') {
    ($app->render)('standard', 'case_confirm');
    return;
}

function show_review_page($app) {
    $case = $_SESSION['case'] ?? [];
    $event = $_SESSION['event'] ?? [];

    $db = Database::getInstance()->getConnection();

    // Fetch defendant
    $stmt = $db->prepare("SELECT * FROM defendant WHERE defendant_ID = ?");
    $stmt->execute([$case['defendant_ID']]);
    $defendant = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch lawyer
    $stmt = $db->prepare("SELECT * FROM lawyer WHERE lawyer_ID = ?");
    $stmt->execute([$case['lawyer_ID']]);
    $lawyer = $stmt->fetch(PDO::FETCH_ASSOC);

    ($app->render)('standard', 'confirm_view', [
        'case' => $case,
        'event' => $event,
        'defendant' => $defendant,
        'lawyer' => $lawyer
    ]);
}

function handle_confirm_case($app) {
    $data = $_SESSION['case'] ?? [];
    $event = $_SESSION['event'] ?? [];

    // Check required fields
    if (empty($data['defendant_ID']) || empty($data['charges']) || empty($data['lawyer_ID'])) {
        http_response_code(400);
        echo "Missing required data in session. Please complete all steps.";
        return;
    }

    $db = Database::getInstance()->getConnection();

    try {
        $db->beginTransaction();

        // 1. Create Case
        $stmt = $db->prepare("INSERT INTO caserecord (defendant_ID) VALUES (?)");
        $stmt->execute([$data['defendant_ID']]);
        $caseID = $db->lastInsertId();

        // 2. Add All Charges
        foreach ($data['charges'] as $charge) {
            Charge::create($caseID, [
                'description' => $charge['description'],
                'status'      => $charge['status'] ?? ''
            ]);
        }

        // 3. Link Lawyer
        $stmt = $db->prepare("INSERT INTO case_lawyer (case_ID, lawyer_ID) VALUES (?, ?)");
        $stmt->execute([$caseID, $data['lawyer_ID']]);

        /// 4. Add Court Events (optional)
        if (!empty($data['events'])) {
            foreach ($data['events'] as $event) {
                if (!empty($event['description']) || !empty($event['date']) || !empty($event['location'])) {
                    CourtEvent::create($caseID, $event);
                }
            }
        }


        $db->commit();
        unset($_SESSION['case']);
        unset($_SESSION['event']);

        ($app->set_message)('success', 'Case added successfully.');
        header("Location: " . BASE_URL . "/case/confirm_case");
        exit;

    } catch (PDOException $e) {
        $db->rollBack();
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}
