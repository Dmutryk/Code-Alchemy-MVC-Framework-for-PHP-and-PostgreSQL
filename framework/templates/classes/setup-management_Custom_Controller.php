<?php namespace __app_namespace__\Controllers; use Code_Alchemy\Controllers\Custom_Controller;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\JSON\Displayed_JSON_Output;

/**
 * Class __custom_controller_name__
 * @package __app_namespace__\Controllers
 *
 * Use this Custom Controller component to execute custom code as
 * part of a Dynamic Controller.
 *
 * (c) 2015-2016 Alquemedia SAS <info@alquemedia.com>
 */
class __custom_controller_name__ extends  Custom_Controller {

    /**
     * This Controller is used by Code Alchemy automatically when the user
     * surfs to the corresponding route
     *
     * @param array $scope   This is scope that will be shared to the View/Layout
     * @param array $request_data This is POST+GET data, the same as the $_POST+$_GET super global
     * @param string $layout This indicates which layout will be used. Leave alone for no changes.
     */
    public function __construct( array &$scope, array $request_data, &$layout ){

        // Settings
        new Displayed_JSON_Output(array(

            'may_manage' => !! $this->is_admin(),

            'language' => Code_Alchemy_Framework::instance()->language()

        ));

    }

}