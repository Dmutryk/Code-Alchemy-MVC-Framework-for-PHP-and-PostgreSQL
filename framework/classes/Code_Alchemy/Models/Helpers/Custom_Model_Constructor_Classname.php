<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Custom_Model_Constructor_Classname extends Stringable_Object{

    public function __construct( $model_name ){

        $this->string_representation = "\\".new Namespace_Guess()."\\Model_Constructors\\$model_name";

    }

}