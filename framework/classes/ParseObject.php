<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 18/12/13
 * Time: 02:40 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\apis\Parse;


class ParseObject {

    /**
     * @var null the Object unique id
     */
    private $object_id = null;

    /**
     * @var ParseRestApi pointer to the Parse REST API
     */
    private $rest_api = null;

    /**
     * @var string the Class Name (for Parse)
     */
    protected $class_name = '';

    /**
     * @var bool true if the object exists, meaning it's persisted
     */
    private $exists = false;

    /**
     * @var string an error associated with the last operation
     */
    public $error = '';

    /**
     * @var array of attributes unique to this Object
     */
    private $attributes = array();

    /**
     * @var array of pointed object to include in Fetch()
     */
    private $include = array();

    /**
     * Construct a new ParseObject
     * @param array $query
     * @param bool $debug
     */
    public function __construct( $query = array(), $debug = false ){

        $this->set_class_name();

        if ( $debug ) {
            echo get_called_class(). ": query appears below<br>\r\n";
            print_r( $query );
            echo "<br>\r\n";
        }


        // if we have a query, then post it to the Parse REST API, as a Fetch()
        if ( count($query) ){

            $json = json_decode($this->api()->fetch( $this->class_name, $query, $debug, $this->include ),true);

            // if we got something
            if ( count( $json['results'])){

                //var_dump( $json );
                $this->attributes = $json['results'][0];
                if ( $debug ) var_dump( $this->attributes);

            } else {

            }

        }

        // if this is ParseMe, we can assume we should fetch my own user
        if ( preg_match('/ParseMe/',get_called_class())){

            $json = json_decode($this->api()->fetch( 'User',array('is_me'=>true), $debug ),true);

            $this->attributes = $json;

        }

    }

    /**
     * @param array $includes to pull from server during fetch
     */
    protected function _include( $includes = array() ){

        foreach ( $includes as $include) array_push( $this->include, $include );

    }

    /**
     * @return array representation of object
     */
    public function as_array(){

        $array = array();

        return $this->attributes;

    }

    /**
     * Set the Class Name for this object
     */
    private function set_class_name(){

        $pieces = explode('\\',get_called_class());

        $this->class_name = ucfirst( $pieces[ (count( $pieces )-1) ]);

    }

    /**
     * @return ParseRestApi
     */
    private function api(){

        if ( ! $this->rest_api ) {

            $options = \x_objects::instance()->configuration()->Parse;

            $this->rest_api = new ParseRestApi(array(
                'application_id'=>(string)$options->application_id,
                'rest_api_key'=>(string)$options->rest_api_key
            ));

        }

        return $this->rest_api;

    }

    /**
     * Save the object, either creating a newone, or updating an existing one
     * @param array $values
     * @return mixed
     */
    public function save($values = array()){

        $api = $this->api();

        $result = $this->exists?

            $api->update( $this->class_name, $this->object_id, $values):

            $api->create( $this->class_name, array_merge( $values,$this->attributes) );

        if ( ! $result ) $this->error = $api->get_error();

        /**
         * Check for save errors
         *
         */
        if ( isset( $result['error'])){

            $this->error = $result['error'];
            $result = false;
        }

        return $result;

    }

    /**
     * @return bool true if the Object exists, in Parse
     */
    public function exists(){ return $this->exists; }

    /**
     * Set the Object from an array of attributes
     * @param $attributes
     * @return ParseObject $this
     */
    public function set( $attributes ){

        foreach ( $attributes as $name => $value )
            $this->attributes[ $name ] = $value;

        return $this;

    }

    /**
     * Get a value, which is an attribute from Parse.com
     * @param string $what
     * @return mixed
     */
    public function __get( $what ){

        $members = $this->as_array();

        return isset( $members[ $what ] )? $members[ $what ] : null;

    }

    /**
     * @return ParseRestApi the "Model" for this class
     */
    public static function model(){

        $options = \x_objects::instance()->configuration()->Parse;

        $api = new ParseRestApi(array(
            'application_id'=>(string)$options->application_id,
            'rest_api_key'=>(string)$options->rest_api_key
        ));

        $api->set_context( get_called_class() );

        return $api;

    }
}