<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/18/15
 * Time: 10:18 PM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Columns_for_REST_Collection
 * @package Code_Alchemy\APIs\Helpers
 *
 * Sets up columns for REST Collection result
 */
class Columns_for_REST_Collection extends Array_Representable_Object{

    /**
     * Columns_for_REST_Collection constructor.
     * @param array $collection
     */
    public function __construct( array $collection ) {

        $result = $collection;

        $num_columns = (new Num_REST_Columns())->int_value();

        if ( $num_columns > 1){

            $count = count($collection);

            $result = array();

            for ( $section = 0; $section< $num_columns; $section++){

                $is_last = ($section==$num_columns-1);

                $result[$section] = $is_last ? array( 'models' => $collection):

                    array( 'models' => array_splice($collection,0,ceil($count/$num_columns)));

            }


        }

        $this->array_values = /*$num_columns >1 ? array( 'columns' => $result): */$result;

    }
}