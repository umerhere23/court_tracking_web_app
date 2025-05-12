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

function edit_charge($app, $chargeID) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    $charge = Charge::getChargeByChargeID($chargeID);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? ''
        ];

        Charge::update($chargeID, $data);

        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    // Render edit form with charge data
    ($app->render)('standard', 'forms/charge_form', [
        'charge' => $charge,
        'isEdit' => true,  // Pass a flag to indicate this is an edit
    ]);
}

function add_charge($app) {
    $caseID = $_GET['caseID'] ?? null;
    if (!$caseID) {
        http_response_code(400);
        echo "Missing case ID.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? ''
        ];

        Charge::create($caseID, $data);

        header("Location: " . BASE_URL . "/case/edit/" . $caseID);
        exit;
    }

    // Render add form with no charge data
    ($app->render)('standard', 'forms/charge_form', [
        'caseID' => $caseID,
        'isEdit' => false,  // Pass a flag to indicate this is an add
    ]);
}
