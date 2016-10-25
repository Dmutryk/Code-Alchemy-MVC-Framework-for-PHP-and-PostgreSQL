<?php


namespace Code_Alchemy\Components\Text;


use Code_Alchemy\Core\Stringable_Object;

class Random_Font_Size extends Stringable_Object{

    public function __construct(){

        $this->string_representation = rand(8,22)."pt";

    }

}