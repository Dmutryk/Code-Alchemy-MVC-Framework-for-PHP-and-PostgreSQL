<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/9/16
 * Time: 2:51 PM
 */

namespace Code_Alchemy\Arrays\Data;


use Code_Alchemy\Core\Array_Representable_Object;

class Emulated_Data_Array extends Array_Representable_Object {


    public function __construct( $model_name, $num_members = 10 ){

        $data = array();

        $id_name = $model_name.'_id';

        for ( $i = 1; $i <= $num_members; $i++ )

            $data[] = array(

                // Add emulated Id
                $id_name => $i,

                // Emulate Name


                'member' => array( 'member' => 'value '));

        $this->array_values = $data;

    }

}