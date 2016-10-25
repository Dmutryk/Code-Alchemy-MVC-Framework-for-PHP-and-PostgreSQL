<?php


namespace __app_namespace__\Controllers;

use Code_Alchemy\APIs\REST_API;
use Code_Alchemy\Controllers\Custom_Controller;
use Code_Alchemy\JSON\Displayed_JSON_Output;
use Code_Alchemy\Store\Cart\Shopping_Cart;

/**
 * Class __custom_controller_name__
 * @package __app_namespace__\Controllers
 *
 * Use this Custom Controller component to execute custom code as
 * part of a Dynamic Controller.
 *
 * This code executes before the following code does:
 *
 * 1) Forms Handler
 * 2) Data Fetcher
 * 3) Layout and View Renderer
 *
 * (c) 2015 Alquemedia SAS <info@alquemedia.com>
 *
 */
class __custom_controller_name__ extends  Custom_Controller {

    /**
     * @param array $data
     * @param array $post_data
     * @param string $layout
     */
    public function __construct( array &$data, array $post_data, &$layout ){

        new Displayed_JSON_Output( new Shopping_Cart($post_data));
    }

}