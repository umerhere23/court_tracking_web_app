<?php

$routes = [];

function get($path, $callback) {
    global $routes;
    $routes['GET'][$path] = $callback;
}

function resolve() {
    global $routes;
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');

    $base = dirname($_SERVER['SCRIPT_NAME']);
    if ($base !== '/' && strpos($uri, $base) === 0) {
        $uri = substr($uri, strlen($base));
    }
    $uri = $uri ?: '/';

    if (isset($routes[$method][$uri])) {
        $app = new stdClass();
        $app->render = function($layout, $content) use (&$app) {
            DEFINE("VIEWS", __DIR__ . '/../views');
            DEFINE("PARTIALS", VIEWS . '/partials');
        
            // Extract $app->results, $app->query, etc. as local variables
            foreach (get_object_vars($app) as $key => $value) {
                $$key = $value;
            }
        
            require VIEWS . "/{$layout}.layout.php";
        };
        
        $app->set_message = function($key, $value) use (&$app) {
            $app->$key = $value;
        };
        $routes[$method][$uri]($app);
    } else {
        http_response_code(404);
        echo "404 Not Found";
    }
}
