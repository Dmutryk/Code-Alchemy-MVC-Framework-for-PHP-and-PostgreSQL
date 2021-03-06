<?php

namespace __mynamespace__;

/**
 * LAYOUT_PRE_STUB
 *
 * Note: This code is automatically generated by Code Alchemy.
 *
 * (c) 2015 Alquemedia SAS, all rights reserved.
 */

use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Controllers\Dynamic_Controller;

// Get Controller
$controller = get_controller( $this );

// Theme root
$theme_root = "__theme_root__";

// Web root
$webroot = Code_Alchemy_Framework::instance()->webroot();

// The scope gives access to data provided by Controller
$scope = $controller->data_as_object();

?>
