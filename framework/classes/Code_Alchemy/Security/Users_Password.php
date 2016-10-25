<?php


namespace Code_Alchemy\Security;


use Code_Alchemy\Core\Validateable_Entity;

class Users_Password extends Validateable_Entity {

    /**
     * Minimum length for password
     */
    const min_length = 6;

    /**
     * @param string $password
     * @param string $repeated_password
     */
    public function __construct( $password, $repeated_password){

        if ( strlen($password)< self::min_length ){

            $this->is_valid = false;

            $this->reason = "Password is too short; must be at least ". self::min_length. " chars";

            return;

        }

        if ( $password !== $repeated_password ){

            $this->is_valid = false;

            $this->reason = "Passwords don't match";

        }


    }

}