<?php


namespace Code_Alchemy\Arrays;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Array_Real_Values
 * @package Code_Alchemy\Arrays
 *
 * Takes an array, and truncates it after any empty values have been removed
 *
 */
class Array_Real_Values extends Array_Representable_Object {

    /**
     * @param array $values to trim
     */
    public function __construct( array $values ){

        foreach ( $values as $name => $value )

            if ( strlen( trim($value) ) > 0  )

                $this->array_values[$name] = $value;


    }
}