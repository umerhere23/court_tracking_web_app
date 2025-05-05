<?php
session_start();

require_once __DIR__ . '/../models/Defendant.php';

function handle_add_defendant($app) {
    try {
        if ($_POST['action'] === 'add_new') {
            // creating new defendant
            $defendantID = Defendant::create($_POST);
        } elseif ($_POST['action'] === 'select_existing' && !empty($_POST['defendant_ID'])) {
            // assigning existing case to a defendent
            $defendantID = $_POST['defendant_ID'];
        } else {
            throw new Exception("Please select or add a defendant.");
        }
        
        $_SESSION['case']['defendant_ID'] = $defendantID;
        header("Location: " . BASE_URL . "/charge/add");
        exit;

    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}

function handle_search_defendants($app) {
    $model = new Defendant();
    $query = $_GET ?? [];
    $results = [];

    if (!empty(trim($query['q'] ?? '')) && !empty($query['field'])) {
        $results = $model->search_fielded($query['field'], $query['q']);
    }

    ($app->set_message)('results', $results);
    ($app->set_message)('query', $query);
    ($app->render)('standard', 'search');
}

if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_add_defendant($app);
    } else {
        if (isset($_GET['success'])) {
            ($app->set_message)('success', 'Defendant added successfully.');
        }
        $defendants = (new Defendant())->all();

        ($app->render)('standard', 'defendant_form', ['defendants' => $defendants]);
    }

} elseif ($action === 'search' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    handle_search_defendants($app);
} else {
    http_response_code(405); 
    echo "Method Not Allowed";
}
