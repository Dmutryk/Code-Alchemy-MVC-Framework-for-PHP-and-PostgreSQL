<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/16/15
 * Time: 10:54 PM
 */

namespace Code_Alchemy\Database\Result;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Fetch_Associative_Values
 * @package Code_Alchemy\Database\Result
 *
 * Fetch values from result as associative values
 */
class Fetch_Associative_Values extends Array_Representable_Object{

    /**
     * @param Query_Result $result
     */
    public function __construct( Query_Result $result ){

        $this->array_values = $result->fetch_assoc();

    }

}