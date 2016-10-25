<?php


namespace Code_Alchemy\Directors;


use Code_Alchemy\Specialists\User_Registration_Clerk;
use Code_Alchemy\Security\Officer;
use Code_Alchemy\RequestToken;

class Forms_Submissions_Director extends Process_Director {

    /**
     * @var array of data submitted for processing
     */
    private $data = array();

    /**
     * @var bool true to send output to Firebug
     */
    private $firebug = false;

    /**
     * @param array $data submitted for processing
     */
    public function __construct( array $data ){

        if ( $this->firebug ) \FB::info(get_called_class().": Invoked");

        $this->data = $data;

    }

    /**
     * @return array of result data
     */
    public function process_submissions(){

        $result = array();

        // Did we get a signup?
        if ( isset( $this->data['signup_submitted'])){

            $clerk = new User_Registration_Clerk( $this->data );

            $clerk->perform_duties();

            $result = $clerk->result();

        }

        // Did we get a login
        if ( isset( $this->data['login_submitted']) || isset( $this->data['signin_submitted'])){

            if ( $this->firebug ) \FB::info(get_called_class().": Login detected");


            $ofc = new Officer();

            $bResult = $ofc->login_using($this->data['email'],$this->data['password'],true,new RequestToken(get_called_class()));

            $result = array(
                'login_result'=> !! $bResult,
                'login_error'=>$ofc->last_error
            );

        }


        return $result;

    }

}