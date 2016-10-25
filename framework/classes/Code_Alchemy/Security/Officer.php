<?php

namespace Code_Alchemy\Security;

use Code_Alchemy\Authority\Site_Authority;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Helpers\Model_Class_Verifier;
use Code_Alchemy\Models\Helpers\Supports_Safe_Delete;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Models\Model_Configuration;
use Code_Alchemy\tools\code_tag;

/**
 *
 * The Security Manager handles logins and logouts, as well as checking status,
 * and grabbing the current user
 *
 */

class Officer extends Alchemist {

    /**
     * @var string cookie domain
     */
    private static $cookie_domain = null;

    const COOKIE_TIME_OUT = 30;


    const SALT_LENGTH = 9;

    /**
     * New! We also store the running-as user, which may be different
     * than the logged-in user
     */
    private $running_as_key  = '_running_as';

    /**
     * @var string Lookup for User's Key
     */
    private $user_key_key = '_ukey';

    /**
     * @var string Lookup key for User Id
     */
    private $user_id_key = '_user_id';

    /**
     * @var string Lookup key for Username
     */
    private $username_key = '_username';
    
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
     * @var string Last error in 2 languages
     */
    public $last_error_en_es = '';

    /**
     * @var int My User Id value
     */
    private $my_userid = 0;

    /**
     * @var null My User Object
     */
    private static $me = null;

    /**
     * @var null User that we are running as
     */
    private static $running_as_me = null;

    /**
     * @var int Id of user we are "running as" which may be different
     * than the Id of the user who logged in.
     */
    private $running_as_user = 0;


    /**
     * @var bool true if debugging this component
     */
    private $debug = false;

    /**
     * @var bool true to send output to console
     */
    private $firebug = false;

    /**
     * @var string Context key to distinguish application
     */
    private $context = 'parnassus';

    /**
     * @var string Framework language, for errors
     */
    private $language = 'en';

    /**
     * @param bool $firebug true to log to console
     */
    public function __construct( $firebug = false ){

        $cookie_domain = (string) new Session_Cookie_Domain();

        if ( $cookie_domain )

            ini_set('session.cookie_domain',$cookie_domain);

        $this->firebug = $firebug;

        $this->context = (string) Code_Alchemy_Framework::instance()->configuration()->find('namespace');

        $this->language = (string) Code_Alchemy_Framework::instance()->language();

    }

    /**
     * Sets the cookie domain
     * @param string $cookie_domain
     */
    public function set_cookie_domain( $cookie_domain ){

        self::$cookie_domain = $cookie_domain;

    }

    /**
     * Returns the user currently running as, or null if none
     * @return object
     */
    public function running_as_me(){

        $class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For('user');

        if ( $this->is_admitted()) {

            if (! self::$running_as_me) {

                $query = "user_id='$this->running_as_user'";

                self::$running_as_me =

                    is_integer($this->running_as_user)?


                        (new Model('user'))->find($query) : new Model('user');
            }

            return self::$running_as_me;

        } else

            return new Model('user');

    }

    /**
     * Run as a specific user
     * @param int $user_id
     * @return Officer for chained commands
     */
    public function set_running_as( $user_id ){

        if ( $this->is_admin() ){

            $this->running_as_user = $user_id;

            $_SESSION["$this->context$this->running_as_key"] = $this->running_as_user;

            self::$running_as_me = (new Model('user'))->find($this->user_id_key."='$user_id'");

        }

        return $this;
    }



    /**
     * @param string $caller
     * @return bool true if logged in
     */
    public function is_admitted( $caller = 'Unknown' ){

        return $this->logged_in( $caller );

    }

    /**
     * Returns the user currently logged in, or null if none
     * @return Dynamic_Model
     */
    public function me(){

        $tag = new code_tag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        if ( $this->is_admitted()) {

            if (! self::$me){

                $str = new Key_Column('user')."='$this->my_userid'";

                self::$me = (new Dynamic_Model('user'))->find($str);

            }

            return self::$me;
        }
        else
            return new Model('user');
    }

    /**
     * @return bool true if logged in
     */
    public function logged_in( $caller = 'Unknown' ){

        if ( $this->firebug) \FB::info("logged_in() called by $caller",get_called_class());

        // 1. Set up some variables
        $container = Code_Alchemy_Framework::instance();

        $agent = new security_agent();

        $result = false;

        $tag = new code_tag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        // 2. Check for session variables
        $context_user_id_key = "$this->context$this->user_id_key";

        $context_username_key = "$this->context$this->username_key";

        // 3. check for Cookies
        $context_user_key = "$this->context$this->user_key_key";

        if ( $this->firebug ) \FB::info($_SESSION);

        if ( ! isset($_SESSION[$context_user_id_key]) && ! isset( $_SESSION[$context_username_key] ) ){

            if ( $this->firebug ) \FB::info(get_called_class().": No session information for User");

            if( isset( $_COOKIE[$context_user_id_key]) && isset($_COOKIE[$context_user_key]) ){

                if ( $this->firebug ) \FB::info(get_called_class().": Yes cookie information for User");


                // 4. Filter cookie for security
                $cookie_user_id  = (string) new secure_filter($_COOKIE[$context_user_id_key]);

                // 5. get the user
                $class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For('user');

                $query = new Key_Column('user')."='$cookie_user_id'";

                $user = (new Model('user'))

                    ->find(new Key_Column('user')."='$cookie_user_id'");

                // 6. if doesn't exist, not logged in
                if ( ! $user->exists){

                }

                // coookie expiry
                if( (time() - $user->ctime ) > 60*60*24*self::COOKIE_TIME_OUT) {


                }

                /* Security check with untrusted cookies - dont trust value stored in cookie.
                 * We also do authentication check of the `ckey` stored in cookie matches that stored in database during login
                 */
                $packet = array(
                    'sha_user_ckey'=>sha1($user->ckey),
                    'cookie_userid'=>@$_COOKIE[$context_user_id_key],
                    'cookie_username'=>$_COOKIE[$context_username_key],
                    'username_is_valid'=>$this->is_username($_COOKIE[$context_username_key])
                );

                if( $user->ckey && is_numeric($_COOKIE[$context_user_id_key] ) && $this->is_username($_COOKIE[$context_username_key])

                    && $_COOKIE[$context_user_key] == sha1($user->ckey)  ) {

                    @session_regenerate_id(); //against session fixation attacks.

                    // New! By default, running-as-user is same as logged-in user
                    $_SESSION["$this->context$this->running_as_key"] = $_COOKIE[ $context_user_id_key];


                    $_SESSION[$context_user_id_key] = $_COOKIE[$context_user_id_key];
                    $_SESSION[$context_username_key] = $_COOKIE[$context_username_key];
                    $_SESSION[self::SESSION_USER_AGENT] = md5($_SERVER['HTTP_USER_AGENT']);

                    $result = true;

                } else {

                }
            } else {

            }
        } else {

            $this->my_userid = $_SESSION[$context_user_id_key];

            $this->running_as_user = $_SESSION["$this->context$this->running_as_key"];


            $result = true;

        }

        return $result;
    }


    /**
     * @return Officer
     */
    public function logout(){

        $session = new \SESSION();

        $sess_user_id = $_SESSION["$this->context$this->user_id_key"];

        $cook_user_id = $_COOKIE[ "$this->context$this->user_id_key" ];

        if(isset($sess_user_id) || isset($cook_user_id)) {

            // get user
            $key_Column = new Key_Column('user');

            $search = $key_Column ."='$sess_user_id',OR:$key_Column='$cook_user_id'";

            $user = (new Model('user'))->find($search);

            if ( $user->exists )

               $user->update(array(
                   'ctime'=>'',
                   'ckey'=>''
               ))->put("Logout");

        }

        /************ Delete the sessions****************/
        unset($_SESSION["$this->context$this->user_id_key"]);

        unset($_SESSION["$this->context$this->username_key"]);

        unset($_SESSION["$this->context$this->running_as_key"]);


        unset($_SESSION['user_level']);

        unset($_SESSION[self::SESSION_USER_AGENT]);

        @session_unset();

        @session_destroy();

        /* Delete the cookies*******************/
        @setcookie("$this->context$this->user_id_key", '', time()-60*60*24*self::COOKIE_TIME_OUT, "/",self::$cookie_domain);

        @setcookie("$this->context$this->username_key", '', time()-60*60*24*self::COOKIE_TIME_OUT, "/",self::$cookie_domain);

        @setcookie("$this->context$this->user_key_key", '', time()-60*60*24*self::COOKIE_TIME_OUT, "/",self::$cookie_domain);

        return $this;

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
    public function login_using($username,$password,$remember_me,$request_token = null){

        $tag = new code_tag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        // No username specified?
        if ( ! strlen($username)){

            $this->last_error = $this->language == 'en'? 'Please enter a username or email':'Debes teclar un usuario/correo';

            $this->last_error_en_es = 'Please enter a username or email. Debes teclar un usuario/correo';


            return false;
        }

        // No username specified?
        if ( ! strlen($password)){

            $this->last_error = $this->language == 'en'? 'You must specify a password':'Debes teclar la contraseña';

            $this->last_error_en_es = 'You must specify a password. Debes teclar la contraseña';

            return false;
        }


        // stash and filter
        $data = array(
            'username'=> (string) new secure_filter($username),
            'password'=>(string) new secure_filter($password),
            'remember_me'=>(string) new secure_filter($remember_me),
            'search_field'=>'email'
        );

        $is_deleted_clause = (new Supports_Safe_Delete('user'))->bool_value()

            ? "',is_deleted='0'":"'";

        $search = $data['search_field'] . "='" . $data['username'] . $is_deleted_clause;


        $user = (new Dynamic_Model('user'))->find($search);

        // if no such user
        if ( ! $user->exists){

            $this->last_error = $this->language=='es'?
                $data['username'].": El usuario no existe":
                "User does not exist";

            $this->last_error_en_es = $data['username'].": El usuario no existe. User does not exist";

            return false;
        }

        //check against salt
        $salt = $user->get('salt');

        $password_hash = $this->password_hash($data['password'],

            $salt);

        $users_password = $user->get('password') ;

        if ($users_password === $password_hash) {

            // if user is locked, cannot login
            if ( $user->userStatus == 'Locked'){

                $this->last_error = 'Your account is locked';

                $this->last_error_en_es = 'Your account is locked.  Tu cuenta está bloqueada.';

                return false;
            }


            return $this->login_core($user, $remember_me);

        } else {

            $language = Code_Alchemy_Framework::instance()->language();

            $this->last_error = $language == 'es'?
                'Error de coincidencia de usuario y clave':
                "Authentication error: incorrect username/password pair";

            $this->last_error_en_es =  'Error de coincidencia de usuario y clave. Authentication error: incorrect username/password pair';

            return false;
        }
    }

    /**
     * Attempt to login a user with a given usernam and password
     * @param string $username
     * @param string $password
     * @param bool $remember_me
     * @param \xobjects\RequestToken $request_token
     * @return bool
     */
    public function login_using_password($username,$password,$remember_me,$request_token = null){

        $tag = new code_tag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        // No username specified?
        if ( ! strlen($username)){

            $this->last_error = $this->language == 'en'? 'Please enter a username or email':'Debes teclar un usuario/correo';

            $this->last_error_en_es = 'Please enter a username or email. Debes teclar un usuario/correo';


            return false;
        }

        // stash and filter
        $data = array(
            'username'=> (string) new secure_filter($username),
            'password'=>(string) new secure_filter($password),
            'remember_me'=>(string) new secure_filter($remember_me),
            'search_field'=>'email'
        );

        $is_deleted_clause = (new Supports_Safe_Delete('user'))->bool_value()

            ? "',is_deleted='0'":"'";

        $search = $data['search_field'] . "='" . $data['username'] . $is_deleted_clause;


        $user = (new Dynamic_Model('user'))->find($search);

        // if no such user
        if ( ! $user->exists){

            $this->last_error = $this->language=='es'?
                $data['username'].": El usuario no existe":
                "User does not exist";

            $this->last_error_en_es = $data['username'].": El usuario no existe. User does not exist";

            return false;
        }

        $users_password = $user->get('password') ;

        if ($users_password === $password) {

            // if user is locked, cannot login
            if ( $user->userStatus == 'Locked'){

                $this->last_error = 'Your account is locked';

                $this->last_error_en_es = 'Your account is locked.  Tu cuenta está bloqueada.';

                return false;
            }


            return $this->login_core($user, $remember_me);

        } else {

            $language = Code_Alchemy_Framework::instance()->language();

            $this->last_error = $language == 'es'?
                'Error de coincidencia de usuario y clave':
                "Authentication error: incorrect username/password pair";

            $this->last_error_en_es =  'Error de coincidencia de usuario y clave. Authentication error: incorrect username/password pair';

            return false;
        }
    }

    /**
     * login using facebook/twitter or another API service
     */
    public function login_api($user_id){

        // get user
        $user = (new Model('user'))->find(new Key_Column('user')."='$user_id'");
        
        // if no such user
        if ( ! $user->exists){

            $this->last_error = "Authentication error: The specified user does not exist";

            $this->last_error_en_es =

                "Authentication error: The specified user does not exist.  El usuario no existe.";

            if ( $this->firebug ) \FB::warn(get_called_class().": $this->last_error");
            return false;
        }

        return $this->login_core($user,true);

    }

    /**
     * Automatically logs in a User
     * @param $user object the user to login
     * @return bool true if successfully logged in
     */
    public function auto_login_as($user){

        if ( $this->firebug ) \FB::info(get_called_class().": Auto login as User Id ".$user->id() );

        return $this->login_api($user->id());

    }

    private function set_user_data(&$user){

        $stamp = time();
        $ckey = $this->generate_key();
        $user->ctime = $stamp;
        $user->ckey = $ckey;

        /**
         * Set last login date
         */
        $user->last_signin_date = date('Y-m-d H:i:s');
        $user->last_login_date = date('Y-m-d H:i:s');

        // Reset invited back flag
        $user->is_invited_back = false;

        $user->ip_address = $_SERVER['REMOTE_ADDR'];

        $user->num_visits = $user->num_visits + 1;

        // update that user was modified
        $user->last_modified_date = date('Y-m-d H:i:s');

        if ( $user && is_object($user) && $user->exists){

            $id_column = method_exists($user,'key_column')?
                $user->key_column():
                $user->source()->keycol();

            $user->last_modified_by = $user->$id_column;

        }

    }

    /**
     * @return string User session token for cross-domain login
     */
    public function user_session_token(){ return $this->user_session_token; }

    /*
    public function cross_domain_user_session( $token ){

        // get the session
        $user_session = user_session::create("token='$token',is_consumed='0'");

        if ( $user_session->exists ){

            $session = unserialize( $user_session->session );

            $user = models\user::create("id='".$session["$this->context$this->user_id_key"]."'");

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
    */

    /**
     * @return Officer
     */
    public static function fetch(){

        return new Officer();

    }


    /**
     * @return int Id of user that we are "running-as"
     */
    public function running_as(){

        $running_as_user = $this->running_as_user;

        return (int)$running_as_user;

    }


    /**
     * @param Dynamic_Model $user
     * @param bool|false $remember_me
     * @return bool
     */
    private function login_core( Dynamic_Model $user, $remember_me = false ){

        if ( $this->firebug ) \FB::info(get_called_class().": Login Core for User Id ".$user->id());


        // set up the session
        @session_start();

        session_regenerate_id (true); //prevent against session fixation attacks.

        $_SESSION["$this->context$this->user_id_key"]= $user->id();

        $_SESSION["$this->context$this->username_key"] = $user->email;

        $_SESSION[self::SESSION_USER_AGENT] = md5($_SERVER['HTTP_USER_AGENT']);

        // New! By default running-as self
        $_SESSION["$this->context$this->running_as_key"] = $user->email;

        if ( $this->firebug) \FB::info($_SESSION);

        // set user data
        $this->set_user_data($user);

        $user->put("Login Core");

        if ( $remember_me ){

            // set a cookie
            $var1 = $_SESSION["$this->context$this->user_id_key"];

            if ( ! setcookie("$this->context$this->user_id_key", $var1, time()+60*60*24*self::COOKIE_TIME_OUT,'/',self::$cookie_domain))

                \FB::warn(get_called_class().": Failed to create cookie!");

            else {

                if ( $this->firebug ) \FB::info(get_called_class().": Successfully created cookie $var1");
            }


            setcookie("$this->context$this->user_key_key", sha1($user->ckey), time()+60*60*24*self::COOKIE_TIME_OUT, "/",self::$cookie_domain);

            setcookie("$this->context$this->username_key",$_SESSION["$this->context$this->username_key"], time()+60*60*24*self::COOKIE_TIME_OUT, "/",self::$cookie_domain);

            if ( $this->firebug ) \FB::info($_COOKIE);


        }
        return true;

    }

    /**
     * @return bool true if I'm an admin user
     */
    public function is_admin(){

        return (new Site_Authority())->is_administrative_user();

    }

    /**
     * Redirect handler
     */
    public function redirect_handler(){

        header('Location: /login');

    }
}
