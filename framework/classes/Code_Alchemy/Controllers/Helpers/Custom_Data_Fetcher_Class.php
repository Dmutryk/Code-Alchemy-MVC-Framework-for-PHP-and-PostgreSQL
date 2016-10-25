<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Custom_Data_Fetcher_Class extends Stringable_Object{

    public function __construct( $class_canonical_name ){

        $this->string_representation = "\\".(string) new Namespace_Guess()."\\Data_Fetcher\\$class_canonical_name";

    }

}