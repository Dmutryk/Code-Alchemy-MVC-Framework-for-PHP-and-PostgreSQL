<?php


namespace Code_Alchemy\Email;


use Code_Alchemy\Core\Validateable_Entity;

/**
 * Class Email_Address
 * @package Code_Alchemy\Email
 *
 * Email Address Class Representation
 */
class Email_Address extends Validateable_Entity{


    public function __construct( $email ){

        $this->is_valid = !! preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/", $email);

    }

}