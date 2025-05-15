<?php
session_start();

// define Defendant DB fields
$FIELDS = [
    'name',
    'email',
    'phone',
    'firm'
];

require_once __DIR__ . '/../models/Lawyer.php';

switch ($action) {
    // direct user to correct page
    case 'edit':
        save_lawyer($app, $FIELDS, $lawyerID);
        break;
    case 'delete':
        delete_lawyer($app, $lawyerID);
        break;
    case 'add':
        save_lawyer($app, $FIELDS);
        break;
    case 'manage':
        show_manage_lawyers($app);
        break;
    default:
        http_response_code(405); 
        echo "Method Not Allowed";
        exit;
}

function save_lawyer($app, $FIELDS, $lawyerID = null) {
    // edit and add functionality combined into a single function
    // do not define defendantID if using for adding
    $isEdit = !is_null($lawyerID);
    $lawyer = null;

    if ($isEdit) { // if edit, fetch data
        $rows = Lawyer::getLawyerByLawyerId($lawyerID);
        $lawyer = $rows[0] ?? null;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [];
        foreach ($FIELDS as $field) {
            $data[$field] = $_POST[$field] ?? null;
        }

        // server side checking - there is also already client-side
        if (empty($data['name'])) {
            $failMessage = urlencode("Lawyer NOT " . ($isEdit ? "edited" : "added") . ". Error with input.");
            header("Location: " . BASE_URL . "/lawyers?success={$failMessage}");
            exit;
        }

        if ($isEdit) { // update database
            Lawyer::update($lawyerID, $data);
            $successMessage = urlencode("Lawyer edited successfully.");
        } else {
            Lawyer::create($data);
            $successMessage = urlencode("Lawyer added successfully.");
        }

        header("Location: " . BASE_URL . "/lawyers?success={$successMessage}");
        exit;
    }

    ($app->render)('standard', 'forms/lawyer_form', [
        'lawyer' => $lawyer,
        'isEdit' => $isEdit,
    ]);
}


function delete_lawyer($app, $lawyerID) {

    Lawyer::delete($lawyerID);

    // Keep user on same page
    $successMessage = urlencode("Lawyer deleted successfully.");
    header("Location: " . BASE_URL . "/lawyer/manage?success={$successMessage}");
    exit;
}

function show_manage_lawyers($app) {
    // get all lawyers from DB
    $lawyers = Lawyer::getAllLawyersWithDetails();

    ($app->render)('standard', 'all_entities/all_lawyers', [
        'lawyers' => $lawyers
    ]);
}