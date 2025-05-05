<?php
session_start();

require_once __DIR__ . '/../models/CourtEvent.php';

function handle_add_event($app) {
    try {
        // save data to session for Case to add to database
        $_SESSION['event']['description'] = $_POST['description'] ?? '';
        $_SESSION['event']['date'] = $_POST['date'] ?? ''; 
        $_SESSION['event']['location'] = $_POST['location'] ?? '';

        header("Location: " . BASE_URL . "/case/confirm");
        
        exit;
    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }
}

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