<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 18/12/13
 * Time: 02:54 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\apis\Parse;


use Code_Alchemy\rest_api;

class ParseRestApi extends \xo_rest_api {

    /**
     * The EndPoint
     */
    const endpoint = 'https://api.parse.com/1/';

    /**
     * @var null the Application Id
     */
    private $application_id = '';

    /**
     * @var string the REST API Key
     */
    private $rest_api_key = '';

    /**
     * @var array of HTTP headers for cURL operations
     */
    private $http_headers = array();

    /**
     * @var string current context, useful for fetches across a specific Model
     */
    private $context = '';

    /**
     * @var string the true context, which doesn't have any slashes for namespaces
     */
    private $true_context = '';

    /**
     * @param string $context to set
     */
    public function set_context( $context ){

        $this->context = $this->true_context = $context;

        $parts = explode('\\',$this->context);

        if ( count( $parts) ){
            $length = count($parts);
            $this->true_context = $parts[$length-1];
        }

    }

    /**
     * @return string the current context
     */
    public function context(){ return $this->context; }

    /**
     * @param array $options
     * @throws \Exception
     */
    public function __construct( $options = array() ){

        foreach ( $options as $name =>$value)
            if ( property_exists(get_class(),$name))
                $this->$name = $value;

        if ( ! $this->application_id || !$this->rest_api_key )
            throw new \Exception(get_class().": You must specify both an application Id and a REST API Key");
        else {

            /**
             * we will need to set some HTTP headers to allow the API to grant access
             */
            $this->http_headers[] = "X-Parse-Application-Id: $this->application_id";
            $this->http_headers[] = "X-Parse-REST-API-Key: $this->rest_api_key";
            $this->http_headers[] = "Content-Type: application/json";
            $token = isset($_COOKIE['inaf2.session'])?$_COOKIE['inaf2.session']:
                ( isset( $_COOKIE['inaf2_session'])? $_COOKIE['inaf2_session']: null);
            if ( $token) $this->http_headers[] = "X-Parse-Session-Token: $token";

        }

    }

    /**
     * @param $username
     * @param $password
     * @param bool $debug if true prints debug output
     * @return mixed
     */
    public function login( $username, $password, $debug = false ){

        $result = $this->do_get( self::endpoint. "login", 'username='.urlencode($username)."&password=".urlencode($password),$this->http_headers,$debug);

        return $result;
    }

    /**
     * Fetch a Parse.com Object from the remote Parse.com server
     * @param string $class name to fetch
     * @param array $query parameters to limit the search
     * @param bool $debug optional debugging
     * @param array $includes
     * @return mixed
     */
    public function fetch( $class, $query = array(), $debug = false, $includes = array() ){

        $where = ( count( $query) )? "where=".json_encode($query):'';

        if ( $debug ) echo get_class(). ": where = $where \r\n";

        $url = '';

        switch ( $class ){
            case 'User':
                $is_me = isset($query['is_me']) && $query['is_me']?true:false;
                $url = $is_me? self::endpoint."users/me" : self::endpoint."users";
            break;
            default:
                $url = self::endpoint."/classes/".$class;
            break;
        }

        $result = $this->do_get( $url ,$where ,$this->http_headers, $debug, implode(',', $includes) );

        return $result;

    }

    /**
     * Create a new remote Parse Object
     * @param $class
     * @param $values
     * @return mixed
     */
    public function create( $class, $values ){

        echo "creating new remote Object\r\n";

        $result = json_decode($this->do_post($class=='User'? self::endpoint."users/": self::endpoint. "classes/".$class , $values, $this->http_headers ),true);

        return $result;

    }

    /**
     * @return array of Unit Test Method names
     */
    public function get_tests(){
        return array('test_parse');
    }

    /**
     * @return bool true if tests work
     */
    public function test_self(){

        return true;

    }

    /**
     * @return array of current state
     */
    public function get_state(){

        return array();

    }

    /**
     * @param array $query
     * @return array representation of all such Parse.com models
     */
    public function fetch_all_as_array( $query = array() ){

        $results = json_decode($this->fetch($this->true_context,$query),true);

        if ( isset($results['results'])){

            $results = $results['results'];

        } else $results = array();

        return $results;

    }

}