<?php


namespace Code_Alchemy\Models\Validation;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Localization\Localized_Error;
use Code_Alchemy\Models\Model_Configuration;

class Members_Validation {

    /**
     * @var bool
     */
    private $is_valid = true;

    /**
     * @var string
     */
    public $error = '';

    /**
     * @param array $members
     * @param array $required_fields
     * @param array $missing_fields
     */
    public function __construct( array $members, array $required_fields, array &$missing_fields = array() ){

        // Check required fields
        foreach ( $members as $name => $value )

            // if required
            if ( in_array($name,$required_fields) && ! $value ){

                $this->is_valid = false;

                $missing_fields[] = $name;

            }

        foreach ( $required_fields as $required )

            if ( ! isset( $members[$required ])){

                $this->is_valid = false;

                $missing_fields[] = $required;

            }

        if ( count( $missing_fields) ){

            /*
            array_walk($missing_fields,function(&$item,$key){

                $item = (string) new CamelCase_Name($item,'_',' ');

            });
            */

            $leader = implode(',',$missing_fields);

            $this->error = (count($missing_fields)>1)?


                "$leader: ". new Localized_Error("These fields are required") :

                "$leader: ". new Localized_Error("This field is required");

        }

    }

    /**
     * @return bool true if valid
     */
    public function is_valid(){ return !! $this->is_valid; }

}