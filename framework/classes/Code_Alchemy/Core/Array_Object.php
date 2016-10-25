<?php


namespace Code_Alchemy\Core;

/**
 * Class Array_Object
 * @package Code_Alchemy\Core
 *
 * A quick and easy way to represent an Array as an Object
 */
class Array_Object extends Array_Representable_Object {

    /**
     * @param array $members to add to Object
     */
    public function __construct( array $members ){

        $this->array_values = $members;

    }

    /**
     * @param $value
     * @return mixed
     */
    public function get( $value ){

        return @$this->array_values[$value];

    }
}