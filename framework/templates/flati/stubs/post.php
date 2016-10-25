<?php

function get_controller( \__name__\controllers\app_controller $controller ){

    return $controller;
}

/**
 * @param \__name__\controllers\app_controller $controller
 * @return \__name__\components\state
 */
function get_state( \__name__\controllers\app_controller $controller ){

    return $controller->state();
}
?>