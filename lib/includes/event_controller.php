<?php
session_start();

require_once __DIR__ . '/../models/CourtEvent.php';

// internal routing within controller for CRUD operations
switch ($action) {
    case 'edit':
        edit_event($app, $eventID);
        break;
    case 'delete':
        delete_event($app, $eventID);
        break;
    case 'add':
        add_event($app);
        break;
    default:
        ($app->render)('standard', '404');
        exit;
}

function delete_event($app, $eventID) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    CourtEvent::delete($eventID);

    // Redirect back to edit case page
    header("Location: " . BASE_URL . "/case/edit/" . $caseID);
    exit;
}

function edit_event($app, $eventID) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    $event = CourtEvent::getEventByEventID($eventID);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $location = $_POST['location'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';

        // Server-side validation
        if (empty($location) || empty($description) || empty($date)) {
            // If not valid, simply skip the database update and redirect
            header("Location: " . BASE_URL . "/case/edit/" . $caseID);
            exit;
        }

        // If validation passes, update the event
        $data = [
            'location' => $location,
            'description' => $description,
            'date' => $date
        ];

        CourtEvent::update($eventID, $data);

        // Redirect to the case edit page after updating
        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    // Render edit form with event data
    ($app->render)('standard', 'forms/event_form', [
        'event' => $event,
        'isEdit' => true,  // Pass a flag to indicate this is an edit
    ]);
}

function add_event($app) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $location = $_POST['location'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';

        // Server-side validation
        if (empty($location) || empty($description) || empty($date)) {
            header("Location: " . BASE_URL . "/case/edit/" . $caseID);
            exit;
        }

        $data = [
            'location' => $location,
            'description' => $description,
            'date' => $date
        ];

        CourtEvent::create($caseID, $data);

        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    ($app->render)('standard', 'forms/event_form', [
        'caseID' => $caseID,
        'isEdit' => false, 
    ]);
}
