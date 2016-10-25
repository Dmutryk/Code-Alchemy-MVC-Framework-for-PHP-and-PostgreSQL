<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 11:24 AM
 */

namespace Code_Alchemy\Arrays\Filters;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Filter_by_Value
 * @package Code_Alchemy\Arrays\Filters
 *
 * Filter an array by value, keeping only members whose value matches
 */
class Filter_by_Value extends Array_Representable_Object{

    /**
     * @param array $original
     * @param mixed $match_value
     */
    public function __construct( array $original, $match_value ){

        $new = array();

        foreach ( $original as $name => $value ){

            if ( $match_value === $value)

                $new[$name] = $value;
        }

        $this->array_values = $new;

    }
}