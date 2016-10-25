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
 * Extracts a member from an array of Array_Object
 */
class Extract_Member_from_Array_Objects extends Array_Representable_Object{

    /**
     * Extract_Member_from_Array_Objects constructor.
     * @param $member
     * @param array $aArrayObjects
     */
    public function __construct( $member, array $aArrayObjects ) {

        $result = array();

        foreach( $aArrayObjects as $oArrayObject )

            $result[] = $oArrayObject->$member;

        $this->array_values = $result;
    }
}