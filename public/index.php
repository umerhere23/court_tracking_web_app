<?php
DEFINE("LIB", $_SERVER['DOCUMENT_ROOT'] . "/court_tracking_web_app/lib");
DEFINE("VIEWS", LIB . "/views");
DEFINE("PARTIALS", VIEWS . "/partials");

$layout = 'standard';


$content = 'home.view';

if(isset($_GET  ['view']) && !empty($_GET['view'])) {
    $requested = basename($_GET['view']);
    $path = VIEWS . "./{$requested}.php";
    if (file_exists($path)) {
        $content = $requested;
    } else {
        $content = '404.view';
    }
}

require VIEWS . "/{$layout}.layout.php";

?>
