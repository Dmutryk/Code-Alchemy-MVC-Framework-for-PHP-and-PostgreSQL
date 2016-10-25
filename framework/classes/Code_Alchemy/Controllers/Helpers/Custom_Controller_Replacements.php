<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Custom_Controller_Replacements extends Array_Representable_Object{

    /**
     * @param string $custom_controller_name
     */
    public function __construct( $custom_controller_name ){

        $this->array_values = array(
            '/__app_namespace__/'=>(string) new Namespace_Guess(),
            '/__custom_controller_name__/'=>$custom_controller_name,

        );
    }

}