<?php
session_start();
require_once __DIR__ . '/../models/CourtEvent.php';

function handle_add_event($app) {
    try {
        // Initialize events session array if it doesn't exist
        if (!isset($_SESSION['case']['events'])) {
            $_SESSION['case']['events'] = [];
        }

        $description = trim($_POST['description'] ?? '');
        $date        = trim($_POST['date'] ?? '');
        $location    = trim($_POST['location'] ?? '');

        // Only add if all fields are filled
        if ($description !== '' && $date !== '' && $location !== '') {
            $_SESSION['case']['events'][] = [
                'description' => $description,
                'date'        => $date,
                'location'    => $location
            ];
        } elseif ($description !== '' || $date !== '' || $location !== '') {
            // Partial input detected — reject and show message
            throw new Exception("To add an event, you must complete description, date, and location.");
        }



        // Redirect based on which button was clicked
        if (isset($_POST['add_more'])) {
            // User clicked "Add Another Event", stay on the same page
            header("Location: " . BASE_URL . "/event/add");
        } else {
            // User clicked "Confirm and Submit Case", proceed to confirmation page
            header("Location: " . BASE_URL . "/case/confirm");
        }
        exit;

    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }
}

// Handling the action for adding an event
if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_add_event($app);
    } else {
        if (isset($_GET['success'])) {
            ($app->set_message)('success', 'Event added successfully.');
        }
        ($app->render)('standard', 'event_form');
    }
} else {
    http_response_code(405); 
    echo "Method Not Allowed";
}
