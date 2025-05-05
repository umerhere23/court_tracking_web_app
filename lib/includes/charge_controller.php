<?php
session_start();
require_once __DIR__ . '/../models/Charge.php';

function handle_add_charge($app) {
    try {
        if (empty($_POST['description'])) {
            throw new Exception("Charge description is required.");
        }

        $_SESSION['case']['charge_description'] = $_POST['description'];
        $_SESSION['case']['charge_status'] = $_POST['status'] ?? '';

        header("Location: " . BASE_URL . "/lawyer/add");
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

        ($app->render)('standard', 'charge_form');
    }
} else {
    http_response_code(405); 
    echo "Method Not Allowed";
}