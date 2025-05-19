<?php
function render_error($app, $message) {
    ($app->render)('standard', 'error', ['message' => $message]);
    exit;
}