<?php


namespace Code_Alchemy\Controllers;


use Code_Alchemy\Core\REQUEST;
use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Security\Officer;

class Custom_Controller {

    /**
     * @return REQUEST_URI
     */
    public function request_uri(){

        return new REQUEST_URI();

    }

    /**
     * @return REQUEST object
     */
    public function request_data(){

        return new REQUEST();

    }

    /**
     * @return bool true if user is logged in
     */
    public function is_logged_in(){

        return !! (new Officer())->is_admitted(get_called_class());

    }

    /**
     * @return object
     */
    public function user(){

        $me = (new Officer())->me();

        return $me;

    }

    /**
     * @return bool true if current user is an admin
     */
    public function is_admin(){

        return (new Officer())->is_admin();

    }

}