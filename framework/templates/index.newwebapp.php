<?php
// For Firebug
ob_start();

// Set timezone
date_default_timezone_set('America/New_York');

/**
 * This is the default index.php file for your new web application.
 * 
 * In most cases, you will not need to edit this file.
 * 
 * However, for some configurations, it may be necessary to tweak it slightly in order 
 * for the application to run properly
 */


// set the Code_Alchemy directory location:
$codealchemy_location = "__root__/";

// set your webapps directory location
$webapp_location = "__working_dir__";
 
// bootstrap x-objects
require_once( "$codealchemy_location"."include/bootstrap.codealchemy.php" );

// run index bootstrap file
require_once( "$codealchemy_location" . "index.bootstrap.php");


$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

FB::info("Page took ".number_format($time,2) ." seconds to load");



?>
