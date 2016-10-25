<?php

namespace __mynamespace__\beans;

use __mynamespace__\models;

use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\security\secure_filter;
use Code_Alchemy\security\security_agent;
use Code_Alchemy\security_interface;
use Code_Alchemy\tools\code_tag;

/**
 *
 * The Security Manager handles logins and logouts, as well as checking status,
 * and grabbing the current user
 *
 */

class security_manager implements security_interface {

    const COOKIE_TIME_OUT = 365;


    const SALT_LENGTH = 9;

    const COOKIE_USERID = '__appname___uid';
    const COOKIE_USERKEY = '__appname___ukey';
    const COOKIE_USERNAME = '__appname___uname';

    const SESSION_USERID = '__appname___user_id';
    const SESSION_USERNAME = '__appname___username';
    const SESSION_USER_AGENT = 'HTTP_USER_AGENT';


    /**
     * @var string token to access user session from another domain
     */
    private $user_session_token = '';

    /**
     * @var string The last error from any operation
     */
    public $last_error = "";

    /**
     * @var int My User Id value
     */
    private $my_userid = 0;

    /**
     * @var null My User Object
     */
    private static $me = null;

    /**
     * @var bool true if debugging this component
     */
    private $debug = false;

    /**
     * @param string $caller
     * @return bool true if logged in
     */
    public function is_admitted( $caller = 'Unknown' ){

        return $this->logged_in( $caller );

    }

    /**
     * Returns the user currently logged in, or null if none
     * @return models\user|null
     */
    public function me(){

        $tag = new code_tag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        if ( $this->is_admitted())
        {
            if (! self::$me)
                self::$me = models\user::create("id='$this->my_userid'");

            return self::$me;
        }
        else
            return new models\user();
    }

    /**
     * @return bool true if logged in
     */
    public function logged_in( $caller = 'Unknown' ){

        // 1. Set up some variables
        $container = \x_objects::instance();

        $agent = new security_agent();

        $result = false;

        $tag = new code_tag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        // 2. Check for session variables
        if ( ! isset($_SESSION[self::SESSION_USERID]) && ! isset( $_SESSION[self::SESSION_USERNAME] ) ){

            // 3. check for Cookies
            if( isset( $_COOKIE[ self::COOKIE_USERID ]) && isset($_COOKIE[ self::COOKIE_USERKEY]) ){

                // 4. Filter cookie for security
                $cookie_user_id  = (string) new secure_filter($_COOKIE[self::COOKIE_USERID]);

                // 5. get the user
                $user = models\user::create("id='$cookie_user_id'");

                // 6. if doesn't exist, not logged in
                if ( ! $user->exists){


                } else {


                }
                // coookie expiry
                if( (time() - $user->ctime) > 60*60*24*self::COOKIE_TIME_OUT) {


                }

                /* Security check with untrusted cookies - dont trust value stored in cookie.
                 * We also do authentication check of the `ckey` stored in cookie matches that stored in database during login
                 */
                $packet = array(
                    'sha_user_ckey'=>sha1($user->ckey),
                    'cookie_userid'=>@$_COOKIE[ self::COOKIE_USERID ],
                    'cookie_username'=>$_COOKIE[ self::COOKIE_USERNAME ],
                    'username_is_valid'=>$this->is_username($_COOKIE[ self::COOKIE_USERNAME ])
                );

                if( $user->ckey && is_numeric($_COOKIE[ self::COOKIE_USERID ] ) && $this->is_username($_COOKIE[ self::COOKIE_USERNAME ])
                    && $_COOKIE[ self::COOKIE_USERKEY ] == sha1($user->ckey)  ) {
                    session_regenerate_id(); //against session fixation attacks.

                    $_SESSION[self::SESSION_USERID] = $_COOKIE[ self::COOKIE_USERID ];
                    $_SESSION[self::SESSION_USERNAME] = $_COOKIE[ self::COOKIE_USERNAME ];
                    $_SESSION[self::SESSION_USER_AGENT] = md5($_SERVER['HTTP_USER_AGENT']);

                    $result = true;

                } else {

                }
            } else {

            }
        } else {

            $this->my_userid = $_SESSION[self::SESSION_USERID];

            $result = true;

        }

        return $result;
    }


    /**
     * Log user out of system
     */
    public function logout(){

        $session = new \SESSION();

        $sess_user_id = $_SESSION[self::SESSION_USERID];

        $cook_user_id = $_COOKIE[ self::COOKIE_USERID ];

        if(isset($sess_user_id) || isset($cook_user_id)) {

            // get user
            $user = models\user::create("id='$sess_user_id',OR:id='$cook_user_id'");

            if ($user->exists){

                $user->ckey = '';

                $user->ctime = '';

                $user->save();
            }
        }

        /************ Delete the sessions****************/
        unset($_SESSION[self::SESSION_USERID]);

        unset($_SESSION[self::SESSION_USERNAME]);

        unset($_SESSION['user_level']);

        unset($_SESSION[self::SESSION_USER_AGENT]);

        @session_unset();

        @session_destroy();

        /* Delete the cookies*******************/
        @setcookie(self::COOKIE_USERID, '', time()-60*60*24*self::COOKIE_TIME_OUT, "/");

        @setcookie(self::COOKIE_USERNAME, '', time()-60*60*24*self::COOKIE_TIME_OUT, "/");

        @setcookie(self::COOKIE_USERKEY, '', time()-60*60*24*self::COOKIE_TIME_OUT, "/");
    }

    public function is_username($username){ return true; }

    /**
     * generate a key
     */
    protected function generate_key($length = 7){

        $password = "";

        $possible = "0123456789abcdefghijkmnopqrstuvwxyz";

        $i = 0;

        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }



    /**
     * hash a password
     */
    public function password_hash($pwd, $salt = null) {
        if ($salt === null)     {
            $salt = substr(md5(uniqid(rand(), true)), 0, self::SALT_LENGTH);
        }
        else     {
            $salt = substr($salt, 0, self::SALT_LENGTH);
        }
        return $salt . sha1($pwd . $salt);
    }


    /**
     * Attempt to login a user with a given usernam and password
     * @param string $username
     * @param string $password
     * @param bool $remember_me
     * @param \xobjects\RequestToken $request_token
     * @return bool
     */
    public function login_using($username,$password,$remember_me,$request_token){
        global $container;
        $tag = new code_tag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        if ($container->app_debug) echo "$tag->event_format: login using $username, $password<br>\r\n";
        // stash and filter
        $data = array(
            'username'=> (string) new secure_filter($username),
            'password'=>(string) new secure_filter($password),
            'remember_me'=>(string) new secure_filter($remember_me),
            'search_field'=>'email'
        );
        // get user
        $user = models\user::create($data['search_field']."='".$data['username']."',is_deleted='0'");

        // if no such user
        if ( ! $user->exists){
            $this->last_error = "Authentication error: ".$data['username'].": The specified user does not exist";
            return false;
        }
        //check against salt
        if ($user->userPassword === $this->password_hash($data['password'],$user->userSalt)) {

            // if user is locked, cannot login
            if ( $user->userStatus == 'Locked'){

                $this->last_error = 'Your account is locked';
                return false;
            }


            return $this->login_core($user);

        } else {
            $this->last_error = "Authentication error: incorrect username/password pair";
            return false;
        }
    }
    /**
     * login using facebook/twitter or another API service
     */
    public function login_api($user_id){
        // get user
        $user = models\user::create("id='$user_id'");
        // if no such user
        if ( ! $user->exists){
            $this->last_error = "Authentication error: The specified user does not exist";
            return false;
        }

        return $this->login_core($user);

    }

    /**
     * Automatically logs in a User
     * @param $user object the user to login
     * @return bool true if successfully logged in
     */
    public function auto_login_as($user){
        return $this->login_api($user->id);
    }

    private function set_user_data(&$user){
        $stamp = time();
        $ckey = $this->generate_key();
        $user->ctime = $stamp;
        $user->ckey = $ckey;
        $user->last_signin_date = date('Y-m-d H:i:s');
        $user->userDateCurrent = $stamp;
        $user->userIPAddress = $_SERVER['REMOTE_ADDR'];
        $user->userVisits = $user->userVisits + 1;
    }

    /**
     * @return string User session token for cross-domain login
     */
    public function user_session_token(){ return $this->user_session_token; }

    public function cross_domain_user_session( $token ){

        // get the session
        $user_session = \__mynamespace__\user_session::create("token='$token',is_consumed='0'");

        if ( $user_session->exists ){

            $session = unserialize( $user_session->session );

            $user = models\user::create("id='".$session[self::SESSION_USERID]."'");

            if ( $user->exists ){

                $result = $this->auto_login_as( $user );

                if ( $result ){

                    $user_session->is_consumed = true;

                    $user_session->consumption_date = date('Y-m-d H:i:s');

                    $user_session->save();

                }

                return $result;
            }

        }

        return false;

    }

    /**
     * @return security_manager
     */
    public static function fetch(){

        return new self;

    }

    /**
     * @return bool true if successful
     */
    private function login_core( models\user $user ){

        // set up the session
        @session_start();

        session_regenerate_id (true); //prevent against session fixation attacks.

        $_SESSION[self::SESSION_USERID]= $user->id;

        $_SESSION[self::SESSION_USERNAME] = $user->email;

        $_SESSION[self::SESSION_USER_AGENT] = md5($_SERVER['HTTP_USER_AGENT']);

        // set user data
        $this->set_user_data($user);

        $user->save();

        // set a cookie
        setcookie(self::COOKIE_USERID, $_SESSION[self::SESSION_USERID], time()+60*60*24*self::COOKIE_TIME_OUT, "/");

        setcookie(self::COOKIE_USERKEY, sha1($user->ckey), time()+60*60*24*self::COOKIE_TIME_OUT, "/");

        setcookie(self::COOKIE_USERNAME,$_SESSION[self::SESSION_USERNAME], time()+60*60*24*self::COOKIE_TIME_OUT, "/");

        $user_session = \__mynamespace__\user_session::create_from_associative(array(
            'token'=>(string) new Random_Password(50),
            'session'=>serialize(array(
                'user_id'=>$_SESSION[self::SESSION_USERID],
                'user_name'=>$_SESSION[self::SESSION_USERNAME]
            ))
        ));

        if ( $user_session && $user_session->exists ){

            $this->user_session_token = $user_session->token;    // pass to client

        }

        return true;

    }

    /**
     * @return bool true if I'm an admin user
     */
    public function is_admin(){

        return $this->me()->user_type == 'admin';

    }

    /**
     * Redirect handler
     */
    public function redirect_handler(){

        header('Location: /login');

    }
}
