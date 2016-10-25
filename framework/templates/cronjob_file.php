<?php
date_default_timezone_set('America/Bogota');

ob_start();

// set the x_objects directory location:
$codealchemy_location = "__root__";

// set your webapps directory location
$webapp_location = "__working_dir__";

// bootstrap x-objects
require_once( "$codealchemy_location"."include/bootstrap.xobjects.php" );

$container = \Code_Alchemy\Core\Code_Alchemy_Framework::instance();

$job = new \__mynamespace__\jobs\__classname__();

$job->run(true);

?>
