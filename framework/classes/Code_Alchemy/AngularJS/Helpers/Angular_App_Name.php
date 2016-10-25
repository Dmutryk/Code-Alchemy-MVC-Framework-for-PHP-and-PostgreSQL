<?php


namespace Code_Alchemy\AngularJS\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Angular_App_Name extends Stringable_Object{

    public function __construct(){

        $this->string_representation = (string) new Namespace_Guess()."App";

    }
}