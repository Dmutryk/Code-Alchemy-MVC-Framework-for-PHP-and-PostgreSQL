<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/17/15
 * Time: 10:30 AM
 */

namespace Code_Alchemy\Arrays;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Prefix_Array_Members
 * @package Code_Alchemy\Arrays
 *
 * Prefix all members of an array
 */
class Prefix_Array_Members extends Array_Representable_Object{

    /**
     * @param array $original_array
     * @param string $prefix
     */
    public function __construct( array $original_array, $prefix ){

        $new_array = array();

        foreach ( $original_array as $member => $value )

            $new_array[ $prefix.$member] = $value;

        $this->array_values = $new_array;

    }

}