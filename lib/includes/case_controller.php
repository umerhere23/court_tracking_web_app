<?php
require_once __DIR__ . '/../models/Case.php';
require_once __DIR__ . '/../models/Defendant.php';


function handle_add_case($app) {
    try {
        CaseRecord::create($_POST);
        header("Location: " . BASE_URL . "/case/add?success=1");
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}

if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_add_case($app);
    } else {
        if (isset($_GET['success'])) {
            ($app->set_message)('success', 'Case added successfully.');
        }

        $defendantModel = new Defendant();
        $defendants = $defendantModel->all();
        ($app->render)('standard', 'case_form', ['defendants' => $defendants]);
    }    
} else {
    http_response_code(405); 
    echo "Method Not Allowed";
}
