<?php


namespace Code_Alchemy\Forms\Validators;


class Validator {

    /**
     * @var bool true if valid
     */
    protected $is_valid = false;

    /**
     * @return bool true if valid
     */
    public function is_valid(){

        return $this->is_valid;

    }

}