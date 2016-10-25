<?php


namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Stringable_Object;

class Today_Start extends Stringable_Object {

    public function __construct(){

        $this->string_representation = date('Y-m-d'). " 00:00:00";

    }
}