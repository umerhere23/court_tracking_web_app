<?php
require_once __DIR__ . '/../models/Defendant.php';

$model = new Defendant();
$query = $_GET ?? [];
$results = [];

if (!empty(trim($query['q'] ?? '')) && !empty($query['field'])) {
    $results = $model->search_fielded($query['field'], $query['q']);
}

($app->set_message)('results', $results);
($app->set_message)('query', $query);
($app->render)('standard', 'search.view');
?>