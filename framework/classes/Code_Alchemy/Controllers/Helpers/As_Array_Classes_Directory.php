<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class As_Array_Classes_Directory extends Stringable_Object {

    public function __construct( $model_name ){

        $this->string_representation = "app/classes/".(string)new Namespace_Guess()."/As_Array/$model_name";

    }

}