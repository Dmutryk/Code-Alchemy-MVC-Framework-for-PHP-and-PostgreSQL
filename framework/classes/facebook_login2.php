<?php
namespace Code_Alchemy;

/**
 * Component to login via Facebook (server-side flow)
 *
 */

class facebook_login2  {

    /**
     * @var string the Facebook Application Id
     */
    private $app_id = '';

    /**
     * @var string URI to redirect after auth
     */
    private $redirect_uri = '';

    /**
     * @var string the Client Secret
     */
    private $client_secret = '';


    private $uri, $ses,$req,$user,$config;
    public function __construct( $options = array() ){

        /**
         * Set any local properties
         */
        foreach( $options as $name =>$value )
            if ( property_exists(get_class(),$name))
                $this->$name = $value;

        $this->uri = new \REQUEST_URI();
        $this->ses = new \SESSION();
        $this->req = new \REQUEST();
    }
    public function go(){

        // check if we got code from facebook
        // if not, redirect
        if(! $this->req->code) {
            $this->ses->state =  md5(uniqid(rand(), TRUE)); // CSRF protection
            header("Location: "."https://www.facebook.com/dialog/oauth?client_id="
                . $this->app_id
                . "&redirect_uri=" . urlencode($this->redirect_uri)
                . "&state=". $this->ses->state

            );
            // we have a code, so we are authorized
        }
    }


    /**
     * Perform a callback after authorizing Facebook
     * @param array $options to use
     */
    public function callback($options = array()) {

        // avoid CSRF
        if  ($this->ses->state && ($this->ses->state === $this->req->state)) {

            /**
             * Generate a Token URL to obtain the access token
             */
            $token_url = "https://graph.facebook.com/oauth/access_token?"
                . "client_id=" . $this->app_id
                . "&redirect_uri=" . urlencode($this->redirect_uri)
                . "&client_secret=" . $this->client_secret
                . "&code=" . $this->req->code;


            $response = file_get_contents($token_url);

            $params = null;

            parse_str($response, $params);

            /**
             * Save the access token in the session
             */
            $_SESSION['access_token'] = $params['access_token'];

            $graph_url = "https://graph.facebook.com/me?access_token="
                . $this->ses->access_token;

            $user = json_decode(file_get_contents($graph_url),true);

            $session_key = isset($options['session_key'])?$options['session_key']:'facebook_user';

            $_SESSION[ $session_key ] = serialize( $user );

            if (isset( $options['redirect_to'])) header('Location: '.$options['redirect_to']);

        }
    }
}
