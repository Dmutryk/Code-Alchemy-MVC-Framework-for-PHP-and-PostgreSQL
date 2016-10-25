<?php


namespace Code_Alchemy\Core;


use Code_Alchemy\Security\Officer;

class Alchemist {

    /**
     * @var string by default no Error
     */
    protected $_error = '';

    /**
     * @var bool toggle debugging
     */
    protected $_debug = false;

    /**
     * @var bool true to toggle firebug output
     */
    protected $_firebug = false;

    /**
     * Any Alchemist object may expose the login state
     * @return bool true if currently logged in
     */
    public function is_logged_in(){

        return !! (new Officer())->is_admitted();

    }

    /**
     * @return bool true if currenrt user is admin
     */
    public function is_admin(){

        return !! (new Officer())->is_admin();

    }

    /**
     * @return bool true if this is development
     */
    protected function is_development(){

        return !! (new Application_Configuration_File())->is_development();

    }

    /**
     * @return string Error, if any
     */
    public function error(){


        return $this->_error;

    }

}