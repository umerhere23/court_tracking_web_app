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
        case 'confirm':
        default:
            http_response_code(404);
            echo "Invalid step.";
    }

    return;
}

if ($action === 'confirm') {
    handle_confirm_case($app);
    exit;
}

function handle_confirm_case($app) {

    $data = $_SESSION['case'] ?? [];

    if (empty($data['defendant_ID']) || empty($data['charge_description']) || empty($data['lawyer_ID'])) {
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

        // 2. Add Charge
        Charge::create($caseID, [
            'description' => $data['charge_description'],
            'status'      => $data['charge_status'] ?? ''
        ]);

        // 3. Link Lawyer
        $stmt = $db->prepare("INSERT INTO case_lawyer (case_ID, lawyer_ID) VALUES (?, ?)");
        $stmt->execute([$caseID, $data['lawyer_ID']]);

        // 4. Optional: Add Court Event
        if (!empty($data['event_description']) && !empty($data['event_date']) && !empty($data['event_location'])) {
            CourtEvent::create($caseID, [
                'description' => $data['event_description'],
                'date'        => $data['event_date'],
                'location'    => $data['event_location']
            ]);
        }

        $db->commit();
        unset($_SESSION['case']);

        ($app->set_message)('success', 'Case added successfully.');
        header("Location: " . BASE_URL . "/");
        exit;

    } catch (PDOException $e) {
        $db->rollBack();
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}