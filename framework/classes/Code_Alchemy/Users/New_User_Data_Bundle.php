<?php


namespace Code_Alchemy\Users;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Users\Helpers\User_Full_Name;

class New_User_Data_Bundle extends Array_Representable_Object{

    public function __construct( array $raw_data ){

        // split full name into components
        if ( isset( $raw_data['full_name'])){

            $fname = new User_Full_Name($raw_data['full_name']);

            $raw_data = array_merge($raw_data,$fname->as_array());

        }

        $this->array_values = $raw_data;

    }
}