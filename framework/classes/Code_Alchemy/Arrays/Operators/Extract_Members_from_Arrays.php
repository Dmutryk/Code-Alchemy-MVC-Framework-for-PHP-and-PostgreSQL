<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/26/15
 * Time: 12:10 PM
 */

namespace Code_Alchemy\Arrays\Operators;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Extract_Member_from_Array_Objects
 * @package Code_Alchemy\Arrays\Operators
 *
 * Extracts a set of members from an array of arrays
 */
class Extract_Members_from_Arrays extends Array_Representable_Object{

    /**
     * Extract_Members_from_Arrays constructor.
     * @param array $members
     * @param array $ofArrays
     * @param string $unique_key
     */
    public function __construct( array $members, array $ofArrays, $unique_key = '' ) {

        $result = array();

        $keys = [];

        foreach( $ofArrays as $array  ){

            $newArray = [];

            $include = true;

            foreach ( $members as $member){

                if ( $unique_key && $member == $unique_key && in_array($array[$member],$keys))

                    $include = false;

                $keys[] = @$array[$unique_key];

                $newArray[$member] = $array[$member];

            }

            if ( $include ) $result[] = $newArray;
        }

        $this->array_values = $result;
    }
}