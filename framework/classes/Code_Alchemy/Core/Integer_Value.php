<?php


namespace Code_Alchemy\Core;


class Integer_Value extends Alchemist{

    /**
     * @var int Integer Value
     */
    protected $integer_value = 0;

    /**
     * @return int value
     * @throws \Exception
     */
    public function int_value(){

        if ( ! is_int($this->integer_value))

            $this->integer_value = (int)$this->integer_value;

        return $this->integer_value;

    }

}