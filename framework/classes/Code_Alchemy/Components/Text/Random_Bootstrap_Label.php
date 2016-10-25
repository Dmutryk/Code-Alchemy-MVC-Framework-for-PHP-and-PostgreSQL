<?php


namespace Code_Alchemy\Components\Text;


use Code_Alchemy\Core\Stringable_Object;

class Random_Bootstrap_Label extends Stringable_Object{

    public function __construct(){

        $labels = array('default','primary','warning','success','danger');

        $this->string_representation = $labels[(rand(0,4))];

    }
}