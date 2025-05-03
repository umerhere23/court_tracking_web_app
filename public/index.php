<?php
/* SET to display all warnings in development. Comment next two lines out for production mode*/
ini_set('display_errors','On');
error_reporting(E_ERROR | E_PARSE);

/* path to Lib */
DEFINE("LIB",$_SERVER['DOCUMENT_ROOT']."/../lib/");

/* path to views and partials */
DEFINE("VIEWS",LIB."views/");
DEFINE("PARTIALS",VIEWS."/partials");

/* path to MODEL and APP */
DEFINE("MODEL",LIB."/model.php");
DEFINE("APP",LIB."/application.php");

/* define layout */
DEFINE("LAYOUT","standard");

require APP;

require MODEL;

get("/defendant/add", function($app) {
    return $app->render("standard", "defendant_form");
});

resolve();