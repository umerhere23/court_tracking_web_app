<?php
session_start();

require_once __DIR__ . '/../models/CourtEvent.php';

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
        http_response_code(405); 
        echo "Method Not Allowed";
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
        $data = [
            'location'=> $_POST['location'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $_POST['date'] ?? ''
        ];

        CourtEvent::update($eventID, $data);

        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    // Render edit form with charge data
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
        $data = [
            'location'=> $_POST['location'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $_POST['date'] ?? ''
        ];

        CourtEvent::create($caseID, $data);

        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    // Render add form with no charge data
    ($app->render)('standard', 'forms/event_form', [
        'caseID' => $caseID,
        'isEdit' => false,  // Pass a flag to indicate this is an add
    ]);
}
