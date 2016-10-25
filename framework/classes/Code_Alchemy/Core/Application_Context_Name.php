<?php


namespace Code_Alchemy\Core;


use Code_Alchemy\Helpers\Namespace_Guess;

class Application_Context_Name extends Stringable_Object {

    public function __construct(){

        $this->string_representation = (string) new Namespace_Guess();

    }
}