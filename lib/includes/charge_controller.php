<?php
session_start();
require_once __DIR__ . '/../models/Charge.php';

function handle_add_charge($app) {
    try {
        // Initialize the charges session array if it doesn't exist
        if (!isset($_SESSION['case']['charges'])) {
            $_SESSION['case']['charges'] = [];
        }

        $description = trim($_POST['description'] ?? '');

        // If the user submitted a non-empty description, save it
        if ($description !== '') {
            $_SESSION['case']['charges'][] = [
                'description' => $description,
                'status' => $_POST['status'] ?? ''
            ];
        }

        // If they clicked "Next" but no valid charges exist, block them
        if (!isset($_POST['add_more']) && count($_SESSION['case']['charges']) === 0) {
            throw new Exception("You must add at least one charge before continuing.");
        }

        // Redirect based on which button was clicked
        if (isset($_POST['add_more'])) {
            header("Location: " . BASE_URL . "/charge/add");
        } else {
            header("Location: " . BASE_URL . "/lawyer/add");
        }
        exit;

    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }
}

if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_add_charge($app);
    } else {
        if (isset($_GET['success'])) {
            ($app->set_message)('success', 'Charge saved to session.');
        }

        ($app->render)('standard', 'charge_form', [
            'charges' => $_SESSION['case']['charges'] ?? []  // Pass the charges for display
        ]);
    }
} else {
    http_response_code(405); 
    echo "Method Not Allowed";
}