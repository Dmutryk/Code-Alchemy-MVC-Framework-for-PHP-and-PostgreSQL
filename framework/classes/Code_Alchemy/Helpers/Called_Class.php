<?php


namespace Code_Alchemy\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class Called_Class extends Stringable_Object{

    public function __construct( $object ){

        $get_called_class = get_class($object);

        $parts = explode('\\', $get_called_class);

        $this->string_representation = count($parts)? (string)array_pop($parts):'';

    }
}