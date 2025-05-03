<?php
require_once '../lib/includes/mouse.php';

get('/', function($app) {
    ($app->render)('standard', 'home.view');
});

get('/search', function($app) {
    require_once '../lib/includes/search_controller.php';
});

resolve();
