<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 11:18 AM
 */

namespace Code_Alchemy\Arrays\Filters;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Trim_Values
 * @package Code_Alchemy\Arrays\Filters
 *
 * Trims each value in the array
 */
class Trim_Values extends Array_Representable_Object{

    public function __construct( array $original ){

        $new = array();

        foreach ( $original as $name => $value )

            $new[$name] = trim($value);

        $this->array_values = $new;


    }
}