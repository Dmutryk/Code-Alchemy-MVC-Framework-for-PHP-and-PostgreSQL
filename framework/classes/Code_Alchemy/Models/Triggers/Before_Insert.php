<?php


namespace Code_Alchemy\Models\Triggers;


use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\Helpers\Current_User_Id;
use Code_Alchemy\Security\Officer;

class Before_Insert extends Model_Trigger {

    /**
     * @param $model_name
     * @param array $values
     * @param string $insert_error
     */
    public function __construct( $model_name, array $values, &$insert_error ){

        // Set created by if missing
        if ( ! isset($values['created_by']) || ! $values['created_by'])

            $values['created_by'] = (string) new Current_User_Id();

        if ( $model_name == 'user'){

            if ( isset( $values['password']) && $values['password']){

                $values['salt'] = (string) new Random_Password(100);

                $values['password'] = (new Officer())->password_hash($values['password'],$values['salt']);

            }
        }

        $this->array_values = $values;

        $dummy = false;

        // Fire custom trigger
        $this->fire_trigger('before_insert',$model_name,$dummy,array(),array(),$insert_error);

    }

}