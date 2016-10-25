<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/3/16
 * Time: 6:02 PM
 */

namespace Code_Alchemy\Arrays\Objects;


use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Require_Iterated_Member
 * @package Code_Alchemy\Arrays\Objects
 *
 * Requires Array Object to have an iterated member, for example
 *
 * $oArrayObject->option1,$oArrayObject->option2, $oArrayObject->option3, etc
 *
 * You can specify the minimum # of iterations required
 */
class Require_Iterated_Member extends Boolean_Value{

    /**
     * Require_Iterated_Member constructor.
     * @param Array_Object $object
     * @param $member_name
     * @param int $min_iterations
     */
    public function __construct( Array_Object $object, $member_name, $min_iterations = 1 ) {

        $meets_requirement = true;

        for ( $i =1 ; $i<=$min_iterations; $i++){

            $member = $member_name.$i;

            if ( ! $object->$member ){

                $meets_requirement = false;

                break;
            }

        }

        $this->boolean_value = $meets_requirement;

    }
}