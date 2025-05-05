<?php
session_start();

require_once __DIR__ . '/../models/Defendant.php';
require_once __DIR__ . '/../models/Lawyer.php';
require_once __DIR__ . '/../includes/Database.php';

// Main Add Case Route
if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_add_case($app);
    } else {
        $defendants = (new Defendant())->all();
        $lawyers = (new Lawyer())->all();

        $prefill = $_SESSION['prefill'] ?? [];
        unset($_SESSION['prefill']);

        if (isset($_GET['success'])) {
            ($app->set_message)('success', 'Case added successfully.');
        }

        ($app->render)('standard', 'manage_case', [
            'defendants' => $defendants,
            'lawyers' => $lawyers,
            'prefill' => $prefill,
            'selected_lawyer_id' => $_GET['lawyer_id'] ?? null
        ]);
    }
}

// Sub-action: Create a new defendant mid-form
if ($action === 'create_defendant') {
    $_SESSION['prefill'] = $_POST;

    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO defendant (Name, Date_of_Birth, Address, Ethnicity, Phone_Number, Email)
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['new_defendant_name'],
        $_POST['new_defendant_dob'],
        $_POST['new_defendant_address'],
        $_POST['new_defendant_ethnicity'],
        $_POST['new_defendant_phone'],
        $_POST['new_defendant_email']
    ]);
    $newDefendantID = $db->lastInsertId();

    header("Location: " . BASE_URL . "/case/add?defendant_id=$newDefendantID");
    exit;
}

if ($action === 'create_lawyer') {
    $_SESSION['prefill'] = $_POST;

    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO lawyer (Name, Email, Phone_Number, Firm)
                          VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['new_lawyer_name'],
        $_POST['new_lawyer_email'],
        $_POST['new_lawyer_phone'],
        $_POST['new_lawyer_firm']
    ]);
    $newLawyerID = $db->lastInsertId();

    header("Location: " . BASE_URL . "/case/add?lawyer_id={$newLawyerID}");
    exit;
}

// Main handler for POST form submission (create case + relations)
function handle_add_case($app) {
    $db = Database::getInstance()->getConnection();

    try {
        $db->beginTransaction();

        $defendantID = (int)$_POST['defendant_ID'];

        // 1. Create case
        $stmt = $db->prepare("INSERT INTO caserecord (defendant_ID) VALUES (?)");
        $stmt->execute([$defendantID]);
        $caseID = $db->lastInsertId();

        // 2. Add charge
        $stmt = $db->prepare("INSERT INTO charge (case_ID, Description, Status) VALUES (?, ?, ?)");
        $stmt->execute([
            $caseID,
            $_POST['charge_description'],
            $_POST['charge_status']
        ]);

        // 3. Assign lawyer
        $lawyerID = (int)$_POST['lawyer_ID'];
        $stmt = $db->prepare("INSERT INTO case_lawyer (case_ID, lawyer_ID) VALUES (?, ?)");
        $stmt->execute([$caseID, $lawyerID]);

        // 4. Optional court event
        if (!empty($_POST['event_description'])) {
            $stmt = $db->prepare("INSERT INTO court_event (case_ID, Location, Description, Date) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $caseID,
                $_POST['event_location'],
                $_POST['event_description'],
                $_POST['event_date']
            ]);
        }

        $db->commit();

        header("Location: " . BASE_URL . "/case/add?success=1");
        exit;
    } catch (PDOException $e) {
        $db->rollBack();
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}