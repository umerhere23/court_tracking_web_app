<?php
ini_set('display_errors','On');
error_reporting(E_ERROR | E_PARSE);

require_once '../lib/includes/mouse.php';

get('/', function($app) {
    ($app->render)('standard', 'home.view');
});

get('/search', function($app) {
    require_once '../lib/includes/search_controller.php';
});

// Route: Add defendant (from your friend)
get("/defendant/add", function($app) {
    return ($app->render)("standard", "defendant_form");
});

resolve();