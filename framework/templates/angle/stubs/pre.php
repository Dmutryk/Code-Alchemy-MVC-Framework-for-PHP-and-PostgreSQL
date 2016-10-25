<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/angle/";

// Web root
$webroot = x_objects::instance()->webroot();

$data = $controller->data();

$content = $state->get_content();


?>
