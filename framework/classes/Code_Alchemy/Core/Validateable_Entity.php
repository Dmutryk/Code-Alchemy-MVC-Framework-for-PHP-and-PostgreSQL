<?php


namespace Code_Alchemy\Core;


class Validateable_Entity {

    /**
     * @var bool true if Entity is valid
     */
    protected $is_valid = true;

    /**
     * @var string Reason for invalidity
     */
    protected $reason = '';

    /**
     * @return bool true if valid
     */
    public function is_valid(){ return $this->is_valid; }

    /**
     * @return string Reason for invalidity
     */
    public function reason(){ return $this->reason; }

    /**
     * @return array
     */
    public function as_array(){

        return [

            'is_valid' => $this->is_valid(),

            'reason' => $this->reason()
        ];
    }

}