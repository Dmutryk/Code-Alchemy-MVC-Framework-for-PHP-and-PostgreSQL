<?php


namespace __app_namespace__\Controllers;

use Code_Alchemy\Controllers\Custom_Controller;
use Code_Alchemy\JSON\Displayed_JSON_Output;
use Code_Alchemy\Vendors\Twitter\Bloodhound_Search;

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
 * As such, you can use this code to prepare data for the View, or
 * to override the preset Layout, etc.
 *
 * (c) 2015 Alquemedia SAS <info@alquemedia.com>
 *
 */
class __custom_controller_name__ extends  Custom_Controller {

    /**
     * This Constructor is invoked by Code Alchemy automatically when the User
     * surfs to the Route that corresponds to this Controller
     *
     * @param array $data   This is data that will be consumed by the Layout and Views.
     * @param array $request_data This is POST+GET data, the same as the $_POST+$_GET super global
     * @param string $layout This indicates which layout will be used. Leave alone for no changes.
     */
    public function __construct( array &$data, array $request_data, &$layout ){

        new Displayed_JSON_Output(

            new Bloodhound_Search($this->request_uri()->part(2),$request_data['q'],true)

        );

    }

}