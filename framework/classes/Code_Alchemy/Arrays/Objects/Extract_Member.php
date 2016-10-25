<?php
/**
 * Created by PhpStorm.
 * User: davidvanegas7
 * Date: 1/3/16
 * Time: 6:02 PM
 */

namespace Code_Alchemy\Arrays\Objects;


use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Boolean_Value;
use Code_Alchemy\Database\Database;

/**
 * Class Require_Iterated_Member
 * @package Code_Alchemy\Arrays\Objects
 *
 * Extracts an iterated member from an Array Object and returns a simple array
 * with the extracted values
 *
 */
class Extract_Member extends Array_Representable_Object{

    /**
     * Extract_Iterated_Member constructor.
     * @param Array_Object $object
     * @param $member_name
     * @param int $min_iterations
     */
    public function __construct( Array_Object $object, $member_name, $min_iterations = 20, $model_name = '' ) {

        $this->_firebug = false;

        if ( $this->_firebug ){

            \FB::info(get_called_class().": Extracting $member_name from the following object");

            \FB::info($object->as_array());
        }

        $result = [];


        $member = $member_name;

        $value = $model_name ? (new Database())->real_escape_string(trim($object->$member),$model_name) :

            trim($object->$member);

        if ( $value ) $result[] = $value;




        $this->array_values = $result;

    }
}