<?php


namespace Code_Alchemy\Vendors\Facebook;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Facebook\GraphObject;
use Code_Alchemy\helpers\absolute_redirect_url;
use Code_Alchemy\components\seo_name;
use Code_Alchemy\parnassus;

/**
 * Class Code_Alchemy_Facebook_Broker is a class that mediates between Code_Alchemy
 * Framework, and the Facebook PHP Framework.
 * @package parnassus\integrations
 */
class Facebook_Broker {

    /**
     * @var string Key to lookup token
     */
    private $token_lookup_key = 'parnassus_facebook_access_token';

    /**
     * @var \Facebook\FacebookSession
     */
    private $session;

    /**
     * @var string where to redirect after returning from Facebook
     */
    private $redirect_url = '/';

    /**
     * @var bool log to Firebug
     */
    private $log_to_firebug = false;

    /**
     * @var string Facebook App Id
     */
    private $app_id = '';

    /**
     * @var string Facebook App Secret
     */
    private $app_secret = '';

    /**
     * Fetch a new Broker, passing some options
     * @param array $options to set in the Broker instance
     */
    public function __construct( $options = array() ){

        // set up options
        foreach ( $options as $option => $value )

            if ( property_exists( $this, $option ) )

                $this->$option = $value;

        $config = (object) Code_Alchemy_Framework::instance()->configuration()->facebook;

        $this->app_id = (string) $config->app_id;

        $this->app_secret = (string) $config->app_secret;

        FacebookSession::setDefaultApplication($this->app_id, $this->app_secret);

        if ( $this->log_to_firebug) \FB::log( get_object_vars($this) );

    }

    /**
     * Authorize Facebook User to the App
     * @param array $scope of permissions to Authorize
     */
    public function authorize( $scope = array() ){

        // Get the Helper
        $helper = new FacebookRedirectLoginHelper( (string) new absolute_redirect_url( $this->redirect_url ) );

        $loginUrl = $helper->getLoginUrl($scope);

        //if ( $this->log_to_firebug ) \FB::log("Broker: Login URL is $loginUrl");
        header("Location: $loginUrl");


    }

    /**
     * Establish a session from a previous redirect
     */
    public function session(){

        $result = false;

        $session = null;

        // Get the Helper
        $redirect_url = (string)new absolute_redirect_url($this->redirect_url);

        if ( $this->log_to_firebug ) \FB::log("Broker: redirect URL is $redirect_url");

        $helper = new FacebookRedirectLoginHelper($redirect_url);

        try {

            $session = $helper->getSessionFromRedirect();

        } catch(FacebookRequestException $ex) {

            // When Facebook returns an error
            //\FB::log("Facebook Exception: ".$ex);

        } catch(\Exception $ex) {

            // When validation fails or other local issues
            if ( $this->log_to_firebug ) \FB::log("Exception: ".$ex);

        }

        if ($session) {
            // Logged in
            $this->session = $session;

            $result = true;

            // Save a cookie
            setcookie($this->token_lookup_key,$session->getToken(),time()+60*60*24*30,'/',$_SERVER['HTTP_HOST']);

            // Add to sesssion
            $_SESSION[$this->token_lookup_key] = $session->getToken();

            if ( $this->log_to_firebug ) \FB::log($session);

        } else {

            if ( $this->log_to_firebug )

                if ( $this->log_to_firebug ) \FB::log("Broker: No session obtained");
        }

        return $result;

    }

    /**
     * Invoke the Facebook Graph API!
     * @param string $node to fetch
     * @param array $fields to pull
     * @param string $access_token to use, instead of cached one
     * @param array $values to pass for write operations
     * @return array
     */
    public function graph(
        $node,
        $fields = array(),
        $access_token = '',
        $values = array()
){

        // Set the method
        $method = $this->method();

        $data = null;

        // Establish session
        $token = $access_token ? $access_token : $this->access_token();

        $session = new FacebookSession($token);

        if ( $this->log_to_firebug ) {

            if ( $this->log_to_firebug ) \FB::log($session->getSessionInfo());

            if ( $this->log_to_firebug ) \FB::log("Broker: Node is $node");

        }

        // Add fields if present
        if ( count( $fields )>0)

            $node .="?fields=".(implode(',',$fields));

        $request = new FacebookRequest($session, $method, $node, count($values)?$values:null);

        try {

            $response = $request->execute();

            $object = $this->graph_object( $response->getGraphObject());

            if ( $this->log_to_firebug ) \FB::log($object);

            $data = $object->asArray();


        } catch ( \Exception $ex ){

            $data = array(
                'result'=>'error',
                'error'=>$ex->getMessage()
            );

        }

        //$response = $request->execute();

        if ( $this->log_to_firebug )  \FB::log($data);

        return $data;
    }

    /**
     * @return string API method to use
     */
    private function method(){

        return isset( $_REQUEST['method']) ? strtoupper($_REQUEST['method']): 'GET';

    }

    /**
     * @param GraphObject $o
     * @return GraphObject
     */
    private function graph_object( GraphObject $o ){ return $o; }

    /**
     * @return string Facebook Access Token from Cookie or Session
     */
    private function access_token(){

        $token = '';


        if ( isset($_SESSION[$this->token_lookup_key]))

            $token = $_SESSION[$this->token_lookup_key];

        elseif ( isset( $_COOKIE[$this->token_lookup_key]))

            $token = $_COOKIE[ $this->token_lookup_key ];

        else {

            // Normalize
            $lookup_key = preg_replace('/\./','_',$this->token_lookup_key);

            if ( isset( $_COOKIE[$lookup_key]))

                $token = $_COOKIE[ $lookup_key ];

        }

        if ( $this->log_to_firebug )  \FB::log("Broker: Lookup key is $this->token_lookup_key and retrieved token is $token");

        return $token;
    }


}