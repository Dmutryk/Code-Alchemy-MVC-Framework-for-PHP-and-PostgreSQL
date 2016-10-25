<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 11:16 AM
 */

namespace Code_Alchemy\Arrays\Filters;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Remove_Empty_Values
 * @package Code_Alchemy\Arrays\Filters
 *
 * A simple filter to remove empty values from an array
 */
class Remove_Empty_Values extends Array_Representable_Object{

    public function __construct( array $original ){

        $new = array();

        foreach ( $original as $name => $value )

            if ( $value ) $new[ $name ] = $value;

        $this->array_values = $new;

    }

}