<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 11:28 AM
 */

namespace Code_Alchemy\Arrays\Operators;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Strip_Values_Leave_Keys
 * @package Code_Alchemy\Arrays\Operators
 *
 * Essentially strips all values from an array, leaving the keys
 * as the values, with numeric keys
 */
class Strip_Values_Leave_Keys extends Array_Representable_Object {

    public function __construct( array $original ){

        $new = array();

        foreach ( $original as $name => $value )

            $new[] = $name;

        $this->array_values = $new;

    }
}