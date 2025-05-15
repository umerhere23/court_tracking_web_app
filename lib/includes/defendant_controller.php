<?php
session_start();

require_once __DIR__ . '/../models/Defendant.php';

switch ($action) {
    case 'edit':
        edit_defendant($app, $defendantID);
        break;
    case 'delete':
        delete_defendant($app, $defendantID);
        break;
    case 'add':
        add_defendant($app);
        break;
    case 'search':
        handle_search_defendants($app);
        break;
    default:
        http_response_code(405); 
        echo "Method Not Allowed";
        exit;
}

function handle_search_defendants($app) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
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
}

function add_defendant($app) {
    $FIELDS = [
        'name',
        'dob',
        'address',
        'ethnicity',
        'phone',
        'email'
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $data = [];
        foreach ($FIELDS as $field) {
            $data[$field] = $_POST[$field] ?? null;
        }

        if (empty($data['name']) || empty($data['dob'])) {
            $failMessage = urlencode('Defendant NOT added. Error with input.');
            header("Location: " . BASE_URL . "/defendants?success={$failMessage}");
            exit;
        }

        Defendant::create($data);

        // Redirect to the case edit page after adding the charge
        $successMessage = urlencode('Defendant added successfully.');
        header("Location: " . BASE_URL . "/defendants?success={$successMessage}");
        exit;
    }

    ($app->render)('standard', 'forms/defendant_form', [
        'isEdit' => false, 
    ]);    
}