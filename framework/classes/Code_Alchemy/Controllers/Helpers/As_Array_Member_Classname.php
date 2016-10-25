<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class As_Array_Member_Classname extends Stringable_Object{

    public function __construct( $classfile, $model_name ){

        $this->string_representation = "\\".(string) new Namespace_Guess()."\\As_Array\\$model_name\\".(string)new \file_basename_for($classfile);

    }

}