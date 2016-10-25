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
 * Extracts a member from an array of arrays
 */
class Extract_Member_from_Arrays extends Array_Representable_Object{

    /**
     * Extract_Member_from_Array_Objects constructor.
     * @param $member
     * @param array $aArrayObjects
     */
    public function __construct( $member, array $ofArrays ) {

        $result = array();

        foreach( $ofArrays as $array  )

            $result[] = $array[$member];

        $this->array_values = $result;
    }
}