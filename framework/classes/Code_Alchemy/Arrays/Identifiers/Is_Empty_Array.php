<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/5/15
 * Time: 12:45 AM
 */

namespace Code_Alchemy\Arrays\Identifiers;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Empty_Array
 * @package Code_Alchemy\Arrays\Identifiers
 *
 * True, if array is empty, in the sense of having
 * only empty values
 */
class Is_Empty_Array extends Boolean_Value{

    /**
     * Is_Empty_Array constructor.
     * @param array $array
     */
    public function __construct( array $array ){

        $is_empty = true;

        foreach ( $array as $name => $value )

            if ( $value ){

                $is_empty = false;

                break;
            }

        $this->boolean_value = $is_empty;

    }
}