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

/**
 * @param \__name__\components\state $state
 * @return \xobjects\components\page_content
 */
function get_content( \__name__\components\state $state ){

    return $state->get_content();

}

?>