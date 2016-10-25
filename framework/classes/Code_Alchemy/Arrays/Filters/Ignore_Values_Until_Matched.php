<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 11:34 AM
 */

namespace Code_Alchemy\Arrays\Filters;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Ignore_Values_Until_Matched
 * @package Code_Alchemy\Arrays\Filters
 *
 * Ignore all values until a specific value is matched
 */
class Ignore_Values_Until_Matched extends Array_Representable_Object{

    public function __construct( array $original, $regex ){

        $new = array();

        $not_matched = true;

        foreach ( $original as $name => $value ){

            if ( $not_matched ){

                $match = preg_match($regex,$value);

                if ( $match ){

                    $not_matched = false;

                }


                else continue;

            } else

                $new[$name] = $value;


        }

        $this->array_values = $new;

    }
}