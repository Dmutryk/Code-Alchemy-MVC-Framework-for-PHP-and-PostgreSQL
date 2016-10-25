<?php namespace __app_namespace__\Controllers;use Code_Alchemy\AngularJS\Helpers\AngularJS_Request_Data;use Code_Alchemy\Controllers\Custom_Controller;use Code_Alchemy\HTTP\Send_CORS_Headers;use Code_Alchemy\JSON\Displayed_JSON_Output;

/**
 * Class __custom_controller_name__
 * @package __app_namespace__\Controllers
 *
 * CUSTOM CONTROLLER WITH SUPPORT FOR CORS
 *
 * (c) 2016 Alquemedia SAS <info@alquemedia.com>
 *
 */
class __custom_controller_name__ extends  Custom_Controller {

    /**
     * @param array $data   This is data that will be consumed by the Layout and Views.
     * @param array $request_data This is POST+GET data, combined with data sent from angularjs,
     *  the same as the $_POST+$_GET super global
     * @param string $layout This indicates which layout will be used. Leave alone for no changes.
     */
    public function __construct( array &$data, array $request_data, &$layout ){

        new Send_CORS_Headers();    // Send CORS for cross-browser support

        // TODO Modify as per your requirements; Modificar según tus requierimientos.
        new Displayed_JSON_Output( $request_data );

    }

}