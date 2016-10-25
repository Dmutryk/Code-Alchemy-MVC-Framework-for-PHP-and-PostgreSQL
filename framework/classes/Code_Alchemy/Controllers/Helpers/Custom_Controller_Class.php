<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Custom_Controller_Class extends Stringable_Object {

    public function __construct( $class_name ){

        $this->string_representation = "\\".(string) new Namespace_Guess()."\\Controllers\\$class_name";

    }

}