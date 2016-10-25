<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 9:57 PM
 */

namespace Code_Alchemy\Arrays;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Impose_Association_on_Array
 * @package Code_Alchemy\Arrays
 *
 * Imposes an association on an existing array
 */
class Impose_Association_on_Array extends Array_Representable_Object{

    /**
     * @param array $original_array
     * @param array $imposed_association
     */
    public function __construct( array $original_array, array $imposed_association ){

        $result = array();

        $pos = 0;

        foreach ( $imposed_association as $key ){

            if ( $key )

                $result[ $key ] = $original_array[ $pos++ ];

        }

        $this->array_values = $result;

    }
}