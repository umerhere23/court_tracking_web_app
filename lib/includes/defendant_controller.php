<?php
require_once __DIR__ . '/../models/Defendant.php';

function handle_add_defendant($app) {
    try {
        Defendant::create($_POST);
        header("Location: " . BASE_URL . "/defendant/add?success=1");
        exit;
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
        ($app->render)('standard', 'defendant_form');
    }

} elseif ($action === 'search' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    handle_search_defendants($app);
} else {
    http_response_code(405); 
    echo "Method Not Allowed";
}
