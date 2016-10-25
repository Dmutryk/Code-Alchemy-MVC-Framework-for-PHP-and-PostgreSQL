<?php


namespace Code_Alchemy\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class Website_URL extends Stringable_Object {

    public function __construct(){

        $this->string_representation = "http://".$_SERVER['HTTP_HOST'];

    }
}