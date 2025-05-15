<?php
session_start();

require_once __DIR__ . '/../models/Defendant.php';
require_once __DIR__ . '/../models/Lawyer.php';
require_once __DIR__ . '/../models/CaseRecord.php';
require_once __DIR__ . '/../models/Charge.php';
require_once __DIR__ . '/../models/CourtEvent.php';

switch ($action) {
    case 'defendant':
        handle_defendant_step($app);
        break;
    case 'charges':
        handle_charge_step($app);
        break;
    case 'lawyer':
        handle_lawyer_step($app);
        break;
    case 'events':
        handle_event_step($app);
        break;
    case 'confirm':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handle_confirm_step($app);
        } else {
            show_case_review($app);
        }
        break;
    case 'success':
        ($app->render)('standard', 'case_wizard/case_confirm');
        break;
    case 'manage':
        show_manage_cases($app);
        break;
    case 'edit':
        edit_case($app, $caseID);
        break;
    case 'delete':
        delete_case($app, $caseID);
        break;
    default:
        http_response_code(404);
        echo "Invalid wizard step.";
        exit;
}

function handle_defendant_step($app) {
    try {
        // Get action from POST
        $action = $_POST['action'] ?? null;
        $defendantID = null; // Initialize the defendant ID variable

        // Handle 'add_new' action
        if ($action === 'add_new') {
            if (empty($_POST['name']) || empty($_POST['dob'])) {
                throw new Exception("Defendant's name and DOB are required.");
            }
            // Create new defendant and get the defendant ID
            $defendantID = Defendant::create($_POST);
            $_SESSION['case']['defendant_ID'] = $defendantID; // Store in session

            // Set success message and redirect to defendent step
            $successMessage = urlencode('Defendant added successfully.');
            header("Location: " . BASE_URL . "/case/defendant?success={$successMessage}");
            exit;
        }

        // Handle 'select_existing' action
        if ($action === 'select_existing' && !empty($_POST['defendant_ID'])) {
            $defendantID = $_POST['defendant_ID'];
            $_SESSION['case']['defendant_ID'] = $defendantID; // Store in session

            // Redirect to charges step
            header("Location: " . BASE_URL . "/case/charges");
            exit;
        }

        // If action is not set or not recognized, render form with available defendants
        $defendants = (new Defendant())->all();
        ($app->render)('standard', 'case_wizard/defendant_form', [
            'defendants' => $defendants,
        ]);
        return;

    } catch (Exception $e) {
        // Handle validation exceptions
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    } catch (PDOException $e) {
        // Handle database exceptions
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}

function handle_charge_step($app) {
    try {
        // Initialize charges session array if it doesn't exist
        if (!isset($_SESSION['case']['charges'])) {
            $_SESSION['case']['charges'] = [];
        }

        // If it's a GET request, just render the charge form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Render charge form with current charges
            $charges = $_SESSION['case']['charges'];
            ($app->render)('standard', 'case_wizard/charge_form', [
                'charges' => $charges
            ]);
            return;
        }

        // If it's a POST request, handle the charge form submission
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? '';

        // Handle new charge input
        if ($description !== '') {
            $_SESSION['case']['charges'][] = [
                'description' => $description,
                'status' => $status
            ];
        }

        // If the user hasn't added any charge and tries to proceed, throw an exception
        if (!isset($_POST['add_more']) && count($_SESSION['case']['charges']) === 0) {
            throw new Exception("You must add at least one charge before continuing.");
        }

        // Redirect based on button clicked (add more or proceed)
        if (isset($_POST['add_more'])) {
            header("Location: " . BASE_URL . "/case/charges");
        } else {
            header("Location: " . BASE_URL . "/case/lawyer");
        }
        exit;

    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }
}

function handle_lawyer_step($app) {
    try {
        // Get action from POST
        $action = $_POST['action'] ?? null;
        $lawyerID = null; // Initialize the lawyer ID variable

        // Handle 'add_new' action
        if ($action === 'add_new') {
            if (empty($_POST['name'])) {
                throw new Exception("Lawyer's name is required.");
            }
            // Create new defendant and get the defendant ID
            $lawyerID = Lawyer::create($_POST);
            $_SESSION['case']['lawyer_ID'] = $lawyerID; // Store in session

            // Set success message and redirect to lawyer step
            $successMessage = urlencode('Lawyer added successfully.');
            header("Location: " . BASE_URL . "/case/lawyer?success={$successMessage}");
            exit;
        }

        // Handle 'select_existing' action
        if ($action === 'select_existing' && !empty($_POST['lawyer_ID'])) {
            $lawyerID = $_POST['lawyer_ID'];
            $_SESSION['case']['lawyer_ID'] = $lawyerID; // Store in session

            // Redirect to events step
            header("Location: " . BASE_URL . "/case/events");
            exit;
        }

        // If action is not set or not recognized, render form with available defendants
        $lawyers = (new Lawyer())->all();
        ($app->render)('standard', 'case_wizard/lawyer_form', [
            'lawyers' => $lawyers
        ]);
        return;

    } catch (Exception $e) {
        // Handle validation exceptions
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    } catch (PDOException $e) {
        // Handle database exceptions
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}

function handle_event_step($app) {
    try {
        // Initialize events session array if it doesn't exist
        if (!isset($_SESSION['case']['events'])) {
            $_SESSION['case']['events'] = [];
        }

        // If it's a GET request, just render the events form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Render events form with current events
            $events = $_SESSION['case']['events'];
            ($app->render)('standard', 'case_wizard/event_form', [
                'events' => $events
            ]);
            return;
        }

        // If it's a POST request, handle the event form submission
        $description = trim($_POST['description'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $location = trim($_POST['location'] ?? '');

        // Validate input
        if ($description !== '' && $date !== '' && $location !== '') {
            $_SESSION['case']['events'][] = [
                'description' => $description,
                'date' => $date,
                'location' => $location
            ];
        } elseif ($description !== '' || $date !== '' || $location !== '') {
            // Handle incomplete event data
            throw new Exception("To add an event, you must complete description, date, and location.");
        }

        // Redirect based on button clicked
        if (isset($_POST['add_more'])) {
            header("Location: " . BASE_URL . "/case/events");
            exit;
        } else {
            // Proceed to confirmation step if no "add more"
            header("Location: " . BASE_URL . "/case/confirm");
            exit;
        }

    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }
}

function show_case_review($app) {
    $case = $_SESSION['case'] ?? [];
    $event = $_SESSION['event'] ?? [];

    $db = Database::getInstance()->getConnection();

    // Fetch defendant
    $defendant = fetch_by_id($db, 'defendant', $case['defendant_ID']);
    
    // Fetch lawyer
    $lawyer = fetch_by_id($db, 'lawyer', $case['lawyer_ID']);

    ($app->render)('standard', 'case_wizard/confirm_view', [
        'case' => $case,
        'event' => $event,
        'defendant' => $defendant,
        'lawyer' => $lawyer
    ]);
}

function handle_confirm_step($app) {
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

        // 1. Create Case Record
        $caseID = insert_case_record($db, $data['defendant_ID']);
        
        // 2. Add All Charges
        insert_case_charges($db, $caseID, $data['charges']);
        
        // 3. Link Lawyer
        link_case_lawyer($db, $caseID, $data['lawyer_ID']);
        
        // 4. Add Court Events (optional)
        insert_case_events($db, $caseID, $data['events']);

        $db->commit();
        unset($_SESSION['case']);
        unset($_SESSION['event']);

        header("Location: " . BASE_URL . "/case/success");
        exit;

    } catch (PDOException $e) {
        $db->rollBack();
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}

function fetch_by_id($db, $table, $id) {
    $stmt = $db->prepare("SELECT * FROM $table WHERE {$table}_ID = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insert_case_record($db, $defendantID) {
    $stmt = $db->prepare("INSERT INTO caserecord (defendant_ID) VALUES (?)");
    $stmt->execute([$defendantID]);
    return $db->lastInsertId();
}

function insert_case_charges($db, $caseID, $charges) {
    foreach ($charges as $charge) {
        Charge::create($caseID, $charge);
    }
}

function link_case_lawyer($db, $caseID, $lawyerID) {
    $stmt = $db->prepare("INSERT INTO case_lawyer (case_ID, lawyer_ID) VALUES (?, ?)");
    $stmt->execute([$caseID, $lawyerID]);
}

function insert_case_events($db, $caseID, $events) {
    foreach ($events as $event) {
        if (!empty($event['description']) || !empty($event['date']) || !empty($event['location'])) {
            CourtEvent::create($caseID, $event);
        }
    }
}

function show_manage_cases($app) {

    $cases = CaseRecord::getAllCasesWithDetails();

    ($app->render)('standard', 'all_entities/all_cases', [
        'cases' => $cases
    ]);
}

function delete_case($app, $caseID) {
    try {
        CaseRecord::deleteCaseByID($caseID);
        show_manage_cases($app);
    } catch (Exception $e) {
        http_response_code(400);
        echo $e->getMessage();
    }
}

function edit_case($app, $caseID) {
    $charges = Charge::getChargesByCaseID($caseID);
    $events = CourtEvent::getEventsByCaseID($caseID);

    ($app->render)('standard', 'edit_case', [
        'caseID'  => $caseID,
        'charges' => $charges,
        'events'  => $events
    ]);
}