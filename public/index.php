<?php
//  Context/This is here because going from C, to Django, to PHP makes me forget how words work.
// ($app->render)('standard', 'home') is the weird way function calls happen in php sometimes.
// This is done to avoid confusion with methods. In this case ->render is being called but is defined in compile_app()
// Same occurs with ->set_message //
define('BASE_URL', '/court_tracking_web_app/public');

require_once '../lib/includes/mouse.php';

get('/', function($app) {
    ($app->render)('standard', 'home');
});

path('/defendant/{action}', function($app, $action) {
    require_once __DIR__ . '/../lib/includes/defendant_controller.php';
});

resolve();
