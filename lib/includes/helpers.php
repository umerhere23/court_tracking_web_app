<?php
function render_error($app, $message) {
    ($app->render)('standard', 'error', ['message' => $message]);
    exit;
}

function redirect_with_success($path, $message) {
    $successMessage = urlencode($message);
    header("Location: " . BASE_URL . $path . "?success=" . $successMessage);
    exit;
}

function extract_post_data(array $fields): array {
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $_POST[$field] ?? null;
    }
    return $data;
}