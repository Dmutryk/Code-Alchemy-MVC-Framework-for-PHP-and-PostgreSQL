<?php


namespace Code_Alchemy\beans;


use Code_Alchemy\RequestToken;
use Code_Alchemy\Security\Officer;

class forms_handler {

    /**
     * @var string where to redirect after login
     */
    private $login_redirect = '/';

    /**
     * @var string where to redirect after signup
     */
    private $signup_redirect = '/';


    /**
     * @var array of result data for caller
     */
    private $result = array();

    private $firebug = false;

    /**
     * @param array $data
     * @param $signature
     * @param array $options to set
     */
    public function __construct(
        array $data,
        $signature,
        array $options = array() ){

        // Set options
        foreach ( $options as $name =>$value )

            if ( property_exists($this,$name))

                $this->$name = $value;

        // Get security officer
        $officer = new Officer();

        // Sign in
        if ( isset( $data['signin_submitted'])){

            // try logging in
            $result = $officer->login_using($data['email'],$data['password'],true,new RequestToken($signature));

            if ( $result )

                    header("Location: $this->login_redirect");

            else

                $this->result['signin_error'] = $officer->last_error;

        }

        // Sign up
        if ( isset( $data['signup_submitted'])){

            // add salt
            $data['salt'] = (string) new \random_password(10);

            // Set password
            $data['password'] = $officer->password_hash($data['password'],$data['salt']);

            $user_class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For('user');

            $new_user = $user_class::create_from_associative($data);

            $this->result['signup_result'] = ($new_user&&$new_user->exists)?'success':'error';

            $this->result['signup_error'] = $user_class::$last_class_error;

            // If signed up
            if ( $new_user && $new_user->exists ){

                $new_user = new $user_class("email='$new_user->email'");

                // Autp login
                if ( $officer->auto_login_as( $new_user ) )

                    // go to home page
                    header('Location: '.$this->signup_redirect);

            }

        }

        if ( isset( $_REQUEST['reset_submitted'])){

            $token = $_REQUEST['token'];

            if ( $this->firebug) \FB::log("Token is $token");

            $user_class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For('user');

            $user = new $user_class("token='$token'");

            $result = false;

            if ( $user && $user->exists ){

                $ofc = new Officer();

                $user->password = $ofc->password_hash($_REQUEST['password'],$user->salt);

                $result = $user->save();

                $this->data['reset_result'] = $result?'success':'error';

                $this->data['reset_error'] = $user->save_error;

                if ( $result )

                    header('Location: /acceder?reset=yes');

            } else {

                $this->data['reset_result'] = 'error';

                $this->data['reset_error'] = 'Datos en error';

            }
        }

    }

    /**
     * @return array of results
     */
    public function result(){ return $this->result; }

}