<?php
session_start();

require_once __DIR__ . '/../models/Lawyer.php';

function handle_add_lawyer($app) {
    try {
        if ($_POST['action'] === 'add_new') {
            // assign existing lawyer to case
            $lawyerID = Lawyer::create($_POST);
        } elseif ($_POST['action'] === 'select_existing' && !empty($_POST['lawyer_ID'])) {
            // create new lawyer
            $lawyerID = $_POST['lawyer_ID'];
        } else {
            throw new Exception("Please select or add a lawyer.");
        }

        $_SESSION['case']['lawyer_ID'] = $lawyerID;
        header("Location: " . BASE_URL . "/event/add");
        exit;

    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}

if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_add_lawyer($app);
    } else {
        if (isset($_GET['success'])) {
            ($app->set_message)('success', 'Lawyer added successfully.');
        }
        $lawyers = (new Lawyer())->all();
        ($app->render)('standard', 'lawyer_form', ['lawyers' => $lawyers]);
    }
} else {
    http_response_code(405); 
    echo "Method Not Allowed";
}