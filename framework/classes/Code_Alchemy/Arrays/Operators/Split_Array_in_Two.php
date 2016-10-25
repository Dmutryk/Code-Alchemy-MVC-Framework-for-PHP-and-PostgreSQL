<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/3/15
 * Time: 8:26 PM
 */

namespace Code_Alchemy\Arrays\Operators;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Split_Array_in_Two
 * @package Code_Alchemy\Arrays\Operators
 *
 * Takes an array and splits into two, imposing labels on each half as provided
 */
class Split_Array_in_Two extends Array_Representable_Object{

    /**
     * Split_Array_in_Two constructor.
     * @param array $original_array
     * @param array $labels
     */
    public function __construct( array $original_array, array $labels ){

        $halfway_point = floor(count($original_array) / 2);

        $this->array_values = array(

            $labels[0] => array_slice($original_array,0, $halfway_point),

            $labels[1] => array_slice($original_array,$halfway_point)

        );

    }

}