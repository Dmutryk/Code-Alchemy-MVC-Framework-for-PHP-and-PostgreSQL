<?php


namespace Code_Alchemy\Users\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

class User_Full_Name extends Array_Representable_Object{

    /**
     * @param string $full_name
     */
    public function __construct( $full_name ){

        $names = explode(' ',$full_name);

        $this->array_values = array(
            'first_name'=>'',
            'middle_name'=>'',
            'last_name'=>''
        );

        if ( ! count($names))

            $this->array_values['first_name'] = $full_name;

        elseif( count($names)==1)

            $this->array_values['first_name'] = $names[0];

        elseif ( count($names)==2){

            $this->array_values['first_name'] = $names[0];

            $this->array_values['last_name'] = $names[1];

        } elseif( count($names)==3){

            $this->array_values['first_name'] = $names[0];

            $this->array_values['middle_name'] = $names[1];

            $this->array_values['last_name'] = $names[2];


        }


    }

}