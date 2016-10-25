<?php


namespace __app_namespace__\Controllers;

use Code_Alchemy\APIs\REST_API;
use Code_Alchemy\Controllers\Custom_Controller;

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

        // Add your custom Controller actions here
        /**
         * Instantiate the REST API, and let it do, uh,
         * the REST of the work :-)
         */
        $api = new REST_API(array(

            // Set Index to fetch Model name
            'model_index'=>2,

            // Set the namespace for models
            'model_namespace'=> '__app_namespace__\\models',

            // toggle debugging
            'debug'=>false,

            // Provide an alternate model name
            'alt_model_name'=>$this->alt_model_name(),

            // Should we allow a guest write access?
            'allow_guest'=>$this->allow_guest(),

            // Filters to weed out certain Models
            'filters'=>$this->filters(),

            // Is the user authenticated to your app?
            'is_authenticated'=>$this->is_authenticated(),

            // Toggle console debugging
            'enable_console'=>false,

            // Id of current user
            'current_user_id'=>$this->current_user_id(),

            // May guests read the API?
            'guest_may_read'=> true

        ));

        header('Content-Type: application/json');

        // Process result
        $result = $api->process_request();

        // Show to user
        echo json_encode( $result->as_array() );


    }

    /**
     * @return int Id of current user
     */
    private function current_user_id(){

        // By default, none
        $user_id = 0;

        /**
         * Set the user id here
         */

        return $user_id;

    }

    /**
     * @return bool true if user is authenticated to your app
     */
    private function is_authenticated(){

        return !! $this->is_logged_in();
    }

    /**
     * @return array of filters
     */
    private function filters(){

        // Collect filters as an array
        $filters = array();

        /**
         * Add your custom fiters here
         */

        return $filters;

    }

    /**
     * @return bool true if guest is allowed to write
     */
    private function allow_guest(){

        // No by default
        $allowed = false;

        /**
         * Make your custome checks here
         */

        return $allowed;
    }

    /**
     * @return string Alternate Model Name
     */
    private function alt_model_name(){

        // Use name from URI by default
        $name = $this->request_uri()->part(3);

        /**
         * Make your custom adjustments here
         */

        return $name;

    }
}