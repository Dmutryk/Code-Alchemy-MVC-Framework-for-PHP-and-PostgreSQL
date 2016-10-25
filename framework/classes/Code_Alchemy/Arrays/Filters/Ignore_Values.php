<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 11:43 AM
 */

namespace Code_Alchemy\Arrays\Filters;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Ignore_Values
 * @package Code_Alchemy\Arrays\Filters
 *
 * Ignores indicated values
 */
class Ignore_Values extends Array_Representable_Object{

    /**
     * @param array $original
     * @param array $ignores
     */
    public function __construct( array $original, array $ignores){

        $new = array();

        foreach ( $original as $name => $value )

            if ( ! in_array($value,$ignores))

                $new[$name ] = $value;

        $this->array_values = $new;

    }
}