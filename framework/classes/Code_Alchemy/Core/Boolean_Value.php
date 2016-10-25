<?php


namespace Code_Alchemy\Core;


/**
 * Class Boolean_Value
 * @package Code_Alchemy\Core
 *
 * Calculates a value as Boolean
 */
class Boolean_Value extends Alchemist{

    /**
     * @var bool Boolean Value
     */
    protected $boolean_value = false;

    /**
     * @return bool value
     * @throws \Exception
     */
    public function bool_value(){

        if ( ! is_bool($this->boolean_value))

            throw new \Exception(get_class().": Value must be boolean");

        return $this->boolean_value;

    }

}