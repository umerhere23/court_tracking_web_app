<?php
session_start();

require_once __DIR__ . '/../models/Charge.php';

switch ($action) {
    case 'edit':
        edit_charge($app, $chargeID);
        break;
    case 'delete':
        delete_charge($app, $chargeID);
        break;
    case 'add':
        add_charge($app);
        break;
    default:
        http_response_code(405); 
        echo "Method Not Allowed";
        exit;
}

function add_charge($app) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? '';

        // Server-side validation
        if (empty($description) || empty($status)) {
            header("Location: " . BASE_URL . "/case/edit/" . $caseID);
            exit;
        }

        $data = [
            'description' => $description,
            'status' => $status
        ];

        Charge::create($caseID, $data);

        // Redirect to the case edit page after adding the charge
        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    ($app->render)('standard', 'forms/charge_form', [
        'caseID' => $caseID,
        'isEdit' => false, 
    ]);
}

function edit_charge($app, $chargeID) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    $charge = Charge::getChargeByChargeID($chargeID);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? '';

        // Server-side validation
        if (empty($description) || empty($status)) {
            header("Location: " . BASE_URL . "/case/edit/" . $caseID);
            exit;
        }

        $data = [
            'description' => $description,
            'status' => $status
        ];

        Charge::update($chargeID, $data);

        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    ($app->render)('standard', 'forms/charge_form', [
        'charge' => $charge,
        'isEdit' => true, 
    ]);
}

function delete_charge($app, $chargeID) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    Charge::delete($chargeID);

    // Redirect back to edit case page
    header("Location: " . BASE_URL . "/case/edit/" . $caseID);
    exit;
}