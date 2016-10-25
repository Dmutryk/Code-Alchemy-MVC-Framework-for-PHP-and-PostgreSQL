<?php


namespace Code_Alchemy\Heuristics;


use Code_Alchemy\Core\Stringable_Object;

class Heuristic_Class_Name extends Stringable_Object{

    public function __construct( $base_name ){

        $this->string_representation = "\\Code_Alchemy\\Heuristics\\$base_name";

    }
}