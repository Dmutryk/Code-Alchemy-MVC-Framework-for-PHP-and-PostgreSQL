<?php


namespace Code_Alchemy\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

class PHP_Input extends Array_Representable_Object {

    public function __construct(){

        $input = file_get_contents('php://input');

        $this->array_values = json_decode($input, true);

    }

}