<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/5/15
 * Time: 12:43 AM
 */

namespace Code_Alchemy\Arrays\Filters;


use Code_Alchemy\Arrays\Identifiers\Is_Empty_Array;
use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Remove_Empty_Rows
 * @package Code_Alchemy\Arrays\Filters
 *
 * Remove Rows that don't have any actual values, despite being
 * keyed in the array
 */
class Remove_Empty_Rows extends Array_Representable_Object{

    public function __construct( array $original_data ){

        $new = array();

        foreach( $original_data as $row ){

            // Only if not a row, or if not empty
            if ( ! is_array($row) || ! (new Is_Empty_Array($row))->bool_value())

                $new[] = $row;
        }

        $this->array_values = $new;
    }
}