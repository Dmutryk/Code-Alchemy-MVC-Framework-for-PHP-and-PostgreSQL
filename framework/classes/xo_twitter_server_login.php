<?php
/**
 * User: "David Owen Greenberg" <code@x-objects.org>
 * Date: 23/01/13
 * Time: 12:10 AM
 */
class xo_twitter_server_login {

    /**
     * @var array last access token from any operation
     */
    private $last_access_token = array();

    private $uri, $ses,$req,$user,$config;

    /**
     * @var string local errors occurring in the Class/Object
     */
    private $error = '';

    /**
     * Construct a new component to perform Twitter server login
     * @throws Exception when not properly configured
     */
    public function __construct(){
        global $container;
        $this->uri = new REQUEST_URI();
        $this->ses = new SESSION();
        $this->req = new REQUEST();
        // get configuration
        $this->config = $container->config->twitter;

        // make sure configuration is correct
        if ( ! $this->valid_configuration( $this->config ) ){

            throw new Exception(get_class().": $this->error");

        }
    }

    /**
     * @param SimpleXMLElement $xml
     * @return bool true if valid configuration
     */
    private function valid_configuration( $xml ){
        $result = true;

        if ( ! $xml->logged_in_redirect ){

            $this->error = 'A logged in redirect URL must be specified';

            $result = false;

        }

        return $result;
    }

    public function go(){
        global $container;
        $tag = new xo_codetag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);
        /* Build twitteroauth object with client credentials. */
        $connection = new twitteroauth(
            (string)$this->config->consumer_key,
            (string)$this->config->consumer_secret);

        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken((string)$this->config->oauth_callback);

        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

        /* If last connection failed don't display authorization link. */
        switch ($connection->http_code) {
            case 200:
                /* Build authorize URL and redirect user to Twitter. */
                $url = $connection->getAuthorizeURL($token);
                header('Location: ' . $url);
                break;
            default:
                /* Show notification if something went wrong. */
                echo $connection->http_code. ': Could not connect to Twitter. Refresh the page or try again later.';
        }

    }

    /**
     * Function to handle a Callback from Twitter
     * @param array $options
     * @return bool
     */
    public function callback($options,$logger = null){



        /* If the oauth_token is old redirect to the connect page. */
        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
            if ($logger) $logger->log('info','An old Twitter Oauth token was found');
            $_SESSION['oauth_status'] = 'oldtoken';
            $loc = (string)$this->config->clear_session_url;
            header('Location: '.$loc);
        }

        /* Create twitteroauth object with app key/secret and token key/secret from default phase */
        $connection = new twitteroauth(
            (string)$this->config->consumer_key,
            (string)$this->config->consumer_secret,
            $_SESSION['oauth_token'],
            $_SESSION['oauth_token_secret']);

        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        // save it locally
        $this->last_access_token = $access_token;

        /* Save the access tokens. No   rmally these would be saved in a database for future use. */
        $_SESSION['access_token'] = $access_token;

        if ($logger) $logger->log('debug',"Twitter access token is ".new xo_array($access_token));

        // get user
        $tuser = $connection->get("account/verify_credentials");

        /* If HTTP response is 200 continue otherwise send to connect page to retry */
        if (200 == $connection->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            $_SESSION['status'] = 'verified';
           // echo "user is ".(string) new xo_string($tuser);
            // does user already exist?
            $class = $options['user_class'];
            $field = $options['lookup_column'];
            $user = new $class("$field='$tuser->id'");
            if ( $user->exists){
                // set logged in
                $method = $options['login_method'];
                $user->$method();
                $this->persist_token($user);
                // go back to after login screen
                $loc = (string)$this->config->logged_in_redirect;
                header("Location: $loc");
                //return true;
            } else {
                // create new user
                $user = new $class;
                $user->login_type = 'twitter';
                $user->username = $tuser->screen_name;
                $user->remote_auth_id = $tuser->id;
                $user->save();
                $this->persist_token($user);
                // set logged in
                $method = $options['login_method'];
                $user->$method();
                // redirect
                $loc = (string)$this->config->new_user_redirect;
                header("Location: $loc");
                //return true;
            }

        } else {
            echo $connection->http_code;
            echo new xo_array($connection->http_info);


            /* Save HTTP status for error dialog on connnect page.
            $loc = (string)$this->config->clear_session_url;
            header('Location: '.$loc);
            return;
            */
        }

        /* Remove no longer needed request tokens */
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);


    }

    /**
     * Persist Oauth tokens in user record
     * @param $user
     */
    private function persist_token($user){
        $user->oauth_token = $this->last_access_token['oauth_token'];
        $user->oauth_token_secret = $this->last_access_token['oauth_token_secret'];
        $user->save();
    }
}
