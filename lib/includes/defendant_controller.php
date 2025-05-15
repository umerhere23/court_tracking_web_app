<?php
session_start();

// define Defendant DB fields
$FIELDS = [
    'name',
    'dob',
    'address',
    'ethnicity',
    'phone',
    'email'
];

require_once __DIR__ . '/../models/Defendant.php';

switch ($action) {
    // direct user to correct page
    case 'edit':
        save_defendant($app, $FIELDS, $defendantID);
        break;
    case 'delete':
        delete_defendant($app, $defendantID);
        break;
    case 'add':
        save_defendant($app, $FIELDS);
        break;
    case 'search':
        handle_search_defendants($app);
        break;
    case 'manage':
        show_manage_defendants($app);
        break;
    default:
        http_response_code(405); 
        echo "Method Not Allowed";
        exit;
}

function save_defendant($app, $FIELDS, $defendantID = null) {
    // edit and add functionality combined into a single function
    // do not define defendantID if using for adding
    $isEdit = !is_null($defendantID);
    $defendant = null;

    if ($isEdit) { // if edit, fetch data
        $rows = Defendant::getDefendantByDefendantId($defendantID);
        $defendant = $rows[0] ?? null;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [];
        foreach ($FIELDS as $field) {
            $data[$field] = $_POST[$field] ?? null;
        }

        // server side checking - there is also already client-side
        if (empty($data['name']) || empty($data['dob'])) {
            $failMessage = urlencode("Defendant NOT " . ($isEdit ? "edited" : "added") . ". Error with input.");
            header("Location: " . BASE_URL . "/defendants?success={$failMessage}");
            exit;
        }

        if ($isEdit) { // update database
            Defendant::update($defendantID, $data);
            $successMessage = urlencode("Defendant edited successfully.");
        } else {
            Defendant::create($data);
            $successMessage = urlencode("Defendant added successfully.");
        }

        header("Location: " . BASE_URL . "/defendants?success={$successMessage}");
        exit;
    }

    ($app->render)('standard', 'forms/defendant_form', [
        'defendant' => $defendant,
        'isEdit' => $isEdit,
    ]);
}


function delete_defendant($app, $defendantID) {

    Defendant::delete($defendantID);

    // Keep user on same page
    $successMessage = urlencode("Defendant deleted successfully.");
    header("Location: " . BASE_URL . "/defendant/manage?success={$successMessage}");
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

function show_manage_defendants($app) {
    // get all defendants from DB
    $defendants = Defendant::getAllDefendantsWithDetails();

    ($app->render)('standard', 'all_entities/all_defendants', [
        'defendants' => $defendants
    ]);
}