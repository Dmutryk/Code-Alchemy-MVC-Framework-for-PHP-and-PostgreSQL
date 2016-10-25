<?php


namespace Code_Alchemy\Database;

/**
 * Class Empty_Result
 * @package Code_Alchemy\Database
 *
 * Empty result is used for compatibility in cases where a database
 * error has occurred
 */
class Empty_Result {

    public function fetch_assoc(){

        return array();

    }

    public function fetch_fields(){

        return array();

    }

    public function close(){}

    /**
     * @return array of nothing
     */
    public function fetchAll(){ return array(); }

}