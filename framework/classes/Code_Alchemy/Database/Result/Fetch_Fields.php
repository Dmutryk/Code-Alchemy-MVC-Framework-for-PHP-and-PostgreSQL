<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/17/15
 * Time: 4:52 PM
 */

namespace Code_Alchemy\Database\Result;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Fetch_Fields
 * @package Code_Alchemy\Database\Result
 *
 * Fetch fields from a database table
 *
 */
class Fetch_Fields extends Array_Representable_Object {

    /**
     * @param Query_Result $result
     */
    public function __construct( Query_Result $result ){

        $this->array_values = $result->fetch_fields();



    }

    /**
     * @return array of field names
     */
    public function field_names(){

        $names = array();

        foreach ( $this->array_values as $value )

            $names[] = is_object( $value ) ? $value->name : $value;

        return $names;
    }


}