<?php


namespace Code_Alchemy\Forms\Validators;


class Email_Validator extends Validator {

    public function __construct( $email_address ){

        $this->is_valid = !! filter_var($email_address, FILTER_VALIDATE_EMAIL);

    }
}