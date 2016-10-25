
    /**
     * Allows access to the REST API using
     * http://mysite.com/api/v1/<model_name>/<id>
     */
    public function v1(){

        /**
         * Instantiate the REST API, and let it do, uh,
         * the REST of the work :-)
         */
        $api = new \Code_Alchemy\rest_api(array(

            // Set the namespace for models
            'model_namespace'=> '_mynamespace_\\models',

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
            'current_user_id'=>$this->current_user_id()

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

        // by default, no
        $is_authenticated = false;

        /**
         * Perform your local check here
         */
        return $is_authenticated;

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
            $name = $this->uri()->part(3);

            /**
            * Make your custom adjustments here
            */

            return $name;

       }

