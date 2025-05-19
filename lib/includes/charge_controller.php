<?php
session_start();

require_once __DIR__ . '/../models/Charge.php';
require_once __DIR__ . '/../includes/helpers.php';

// route internally within charge_controller
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
        ($app->render)('standard', '404');
}

function add_charge($app) {
    try {
        $caseID = $_GET['caseID'] ?? null;

        if (!$caseID) { // caseID required for database integrity
            throw new Exception("Case ID required.");
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = $_POST['description'] ?? '';
            $status = $_POST['status'] ?? '';
    
            // Server-side validation
            if (empty($description) || empty($status)) {
                throw new Exception("Missing data for adding a charge.");
            }
    
            $data = [
                'description' => $description,
                'status' => $status
            ];
            
            // perform database operation
            Charge::create($caseID, $data);
            $successMessage = urlencode("Charge added successfully."); // display messages to user
    
            // Redirect to the case edit page after adding the charge
            header("Location: " . BASE_URL . "/case/edit/" . $caseID . '/?success=' . $successMessage);
            exit;
        }
        
        // otherwise GET request, display form for adding charge
        ($app->render)('standard', 'forms/charge_form', [
            'caseID' => $caseID,
            'isEdit' => false, 
        ]);
    } catch (Exception $e) {
        render_error($app, $e->getMessage());
    }
    
}

function edit_charge($app, $chargeID) {
    try {
        $caseID = $_GET['caseID'] ?? null;
        
        // caseID required
        if (!$caseID) {
            throw new Exception("Case ID required.");
        }

        // get the charge object
        $charge = Charge::getChargeByChargeID($chargeID);

        if (!$charge) {
            throw new Exception("Charge not found.");
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = $_POST['description'] ?? '';
            $status = $_POST['status'] ?? '';
    
            // Server-side validation
            if (empty($description) || empty($status)) {
                throw new Exception("Description and status must be filled.");
            }
    
            $data = [
                'description' => $description,
                'status' => $status
            ];
    
            // perform database operation
            Charge::update($chargeID, $data);
            $successMessage = urlencode("Charge updated successfully."); // display messages to user
    
            header("Location: " . BASE_URL . "/case/edit/" . $caseID . '/?success=' . $successMessage);
            exit;
        }
    
        ($app->render)('standard', 'forms/charge_form', [
            'charge' => $charge, // pass details to display on form
            'isEdit' => true, 
        ]);

    } catch (Exception $e) {
        render_error($app, $e->getMessage());
    }
}

function delete_charge($app, $chargeID) {
    try {
        $caseID = $_GET['caseID'] ?? null;
        if (!$caseID) {
            throw new Exception("Case ID required.");
        }
    
        // perform database operation
        Charge::delete($chargeID);
        $successMessage = urlencode("Charge deleted successfully.");
    
        // Redirect back to edit case page
        header("Location: " . BASE_URL . "/case/edit/" . $caseID . '/?success=' . $successMessage);
        exit;     
   
    } catch (Exception $e) {
        render_error($app, $e->getMessage());
    }
}