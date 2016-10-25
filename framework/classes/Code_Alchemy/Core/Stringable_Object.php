<?php


namespace Code_Alchemy\Core;


abstract class Stringable_Object extends Alchemist {

    /**
     * @var string string representation of object
     */
    protected $string_representation = '';

    /**
     * @return string representation of method
     */
    public function __toString(){

        return $this->string_representation;

    }

}