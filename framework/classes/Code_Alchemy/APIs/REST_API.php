<?php
namespace Code_Alchemy\APIs;


use Code_Alchemy\APIs\Helpers\Columns_for_REST_Collection;
use Code_Alchemy\APIs\Helpers\Custom_Field_Search;
use Code_Alchemy\APIs\Helpers\GET_Filters;
use Code_Alchemy\APIs\Helpers\Query_Filters;
use Code_Alchemy\APIs\Helpers\Query_For_REST_Count_Total;
use Code_Alchemy\APIs\Helpers\Request_Method;
use Code_Alchemy\APIs\Helpers\REST_API_Query_for_Model_Factory;
use Code_Alchemy\APIs\Helpers\REST_Collection_Model_Key;
use Code_Alchemy\APIs\Helpers\Set_Request_from_Headers;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\HTTP\Send_CORS_Headers;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Factories\Helpers\Model_Count;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Helpers\Dynamic_Model_Fields_Fetcher;
use Code_Alchemy\Models\Helpers\Fields_Fetcher;
use Code_Alchemy\Models\Helpers\Generic_Model;
use Code_Alchemy\Models\Helpers\Model_Class_For;
use Code_Alchemy\Models\Helpers\Model_Class_Verifier;
use Code_Alchemy\api_specification;
use Code_Alchemy\helpers\model_fields_fetcher;
use Code_Alchemy\Models\Helpers\Unique_Object_Id;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Models\Model_Fields;
use Code_Alchemy\parnassus;
use Code_Alchemy\restful_result;

/**
 * Class rest_api is Code_Alchemy' implementation of a complete REST API, suitable for
 * use with BackboneJS, or any client requiring a true REST API implementation.
 *
 * This package has the benefit of being ridiculously easy to implement, by
 * setting a few simple parameters, and overriding a few basic methods.
 *
 *
 * @package Code_Alchemy\APIs
 *
 * @api
 *
 */
class REST_API extends API_Service implements api_specification {

    /**
     * Allows us to specify which part of the URI has the model name,
     * and which has the optional Id index
     */
    const default_model_index = 3;
    const default_id_index = 4;
    const default_command_index = 5;

    /**
     * @var bool if true enables verbose debugging
     */
    private $debug = false;

    /**
     * @var string an alternate model name, that can be passed from the client
     */
    private $alt_model_name = '';

    private $method = '';

    /**
     * @var string Dynamic Model Key
     */
    private $dynamic_model_key = '';

    private $model_name = '';
    private $object = null;
    private $collection = array();
    private $keycol = '';
    private $object_id = -1;

    /**
     * @var int Index for the Id value
     */
    private $id_index = self::default_id_index;

    /**
     * @var int Index for optional Method
     */
    private $command_index = self::default_command_index;

    /**
     * @var string Optional Command passed with URI
     */
    private $command = '';

    /**
     * @var array of data sent with command
     */
    private $data = array();

    /**
     * @var bool true if a guest may perform write operations
     */
    private $allow_guest = false;

    /**
     * @var bool by default, guests cannot even read the API
     */
    private $guest_may_read = true;

    /**
     * @var array data from API, passed back to client
     */
    private $api_data = array(
        'operation_result'=>'error'
    );

    /**
     * @var string optional session variable name to save the last message
     * from operations like post, put and delete
     */
    private $session_messages = '';

    /**
     * @var array of filters to use when GETting stuff
     */
    private $filters = array();

    /**
     * @var bool true if user is authenticated
     */
    private $is_authenticated = false;

    /**
     * @var bool if true enable console debugging
     */
    private $enable_console = false;


    /**
     * @var int Id of current user performing actions against API
     */
    private $current_user_id = 0;

    private $error_filter = null;

    /**
     * @var int Model Index
     */
    private $model_index = self::default_model_index;

    /**
     * @var Unique_Object_Id
     */
    private $unique_object_id = null;

    /**
     * @var bool true to allow CORS
     */
    private $allow_CORS = false;

    /**
     * @var string List View Model Name
     */
    private $list_view_model_name = '';

    /**
     * @var string query used to fetch collection
     */
    private $collection_query = '';

    /**
     * Construct a new REST API instance
     * @param array $options
     */
    public function __construct($options = array()){

        // Set request from headers
        new Set_Request_from_Headers();

        // Default error filter
        $this->error_filter = function($error){ return $error; };

        // set options
        foreach ( $options as $member=>$value )

            if ( property_exists($this,$member))

                $this->$member = $value;

        // If CORS, send headers
        if ( $this->allow_CORS ) new Send_CORS_Headers();

        // Get a key for Dynamic Model
        $this->dynamic_model_key = (new REQUEST_URI)->part( $this->model_index );

        // Container debugging takes priority
        $this->debug = $this->debug || Code_Alchemy_Framework::instance()->debug;

        $tag = new \xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        $this->options['model_index'] = self::default_model_index;

        parent::__construct($options);

        $this->uri = new REQUEST_URI();

        $this->id_index = $this->options['model_index']+1;

        $this->id_command = $this->options['model_index']+2;

        $this->command = $this->uri->part($this->id_command);

        $this->filters = $this->set_filters( $options );

        $input = file_get_contents('php://input');

        $this->data = json_decode($input, true);

        // Can also fetch from POST
        if ( ! $this->data ) {

            $this->data = $_REQUEST;

        }

        // Get the REQUEST METHOD
        $this->method = (string) new Request_Method( $this->data );

        $this->api_data['server_request_method'] = $this->method;



        // this allows the backend to override data sent from the client
        if (array_key_exists('data', $options)) {
            foreach ($options['data'] as $key => $val) {
                $this->data[$key] = $val;
            }
        }

    }

    /**
     * @return string Model Name
     */
    private function get_model_name(){


        $name = $this->uri->part(isset($this->options['model_index']) ? $this->options['model_index'] : -1);

        if ( preg_match('/([a-z_]+)_page([0-9]+)_size([0-9]+)/',$name,$hits))

                $name = $hits[1];

        if ( $this->list_view_model_name && $this->method == 'GET' ) $name = $this->list_view_model_name;

        return $name;

    }

    /**
     * @return restful_result result of processing the request
     */
    public function process_request(){

        $model_name = $this->get_model_name();

        $unique_Object_Id = (new Unique_Object_Id($model_name));

        if ( ! $model_name) {

            $this->result = false;

            $this->error = 'A Model name was not specified in position '.$this->options['model_index']. ' of the URL';

            // Is this is a Unique Object Id
        } elseif ($unique_Object_Id->is_valid() ) {

            // Save for later use
            $this->unique_object_id = $unique_Object_Id;

            // Get Model from Unique Object Id
            $this->object = $unique_Object_Id->model();

        }else {

            // Get the Model Class
            $this->model_name = (string) new Model_Class_For($model_name);

            if ( ! class_exists( $this->model_name)){

                $this->result = false;

                $this->error = "$this->model_name: No such Model or class found";

            }

            if ( $this->result ){

                $class = $this->model_name;

                if ( ! class_exists( $class)){

                    $this->error = "$class: No such class.  Perhaps you wanted to implement the 'Alternate Model Name' in your REST API setup?";

                    $this->result = false;

                } else {

                    // Get the Key Column
                    $is_dynamic_model = (new Model_Class_Verifier($class))->is_dynamic_model();

                    $dynamic_Model = (new Dynamic_Model($model_name));

                    $this->keycol = $is_dynamic_model ?

                        (string) (new Key_Column($model_name)) :

                            $class::source()->keycol();

                    // Get either an Object Id or a Command
                    $id_or_command = $this->uri->part($this->id_index);

                    // if numeric
                    if ( is_numeric( $id_or_command ))

                        $this->object_id = $id_or_command;


                    else

                        // We got a command
                        $this->command = $id_or_command;

                    $method = "process_".strtolower($this->method);

                    if ( method_exists($this,$method)){

                        $class = $this->model_name;

                        // Get the Factory
                        $list_view_if_available = !!(isset($_REQUEST['_list_view_if_available']) && $_REQUEST['_list_view_if_available']);

                        $model = $is_dynamic_model?

                            $dynamic_Model->get_factory(

                                // Should we use a List View?
                                $list_view_if_available && $this->method == 'GET',

                                $this->list_view_model_name

                            ):

                                $class::model();

                        $this->$method( $model );

                    } else {

                        $this->error = "$this->method: Not implemented yet";

                        $this->result = false;

                    }

                }

            }
        }

        // Normalize error
        $this->api_data['error'] =$this->error;

        $this->api_data['operation_result'] = $this->result?'success':'error';

        $this->api_data['model_name'] = $this->get_model_name();

        $result = new restful_result(
            $this->result?
                ($this->object?array_merge( $this->object->as_array(),array('codeAlchemy_data'=>$this->api_data)):

                    $this->collection_data()
                ):
                array('result'=>'error','error'=>$this->error,
                    'codeAlchemy_data'=>$this->api_data)
        );

        return $result;
    }

    /**
     * @return array of Collection data
     */
    private function collection_data(){

        $include_field_info = isset( $_REQUEST['_field_info']) && (bool) $_REQUEST['_field_info'];

        // Count info
        $count_info = [];

        $collection = $this->collection;

        if ( isset($_REQUEST['_count'])){

            $count_info['count_records'] = count( $this->collection );

            $count_info['total_records'] = (new Model_Count( $this->get_model_name(),

                (string)new Query_For_REST_Count_Total($this->filters)))->int_value();

            $collection = [

                'count_info' => $count_info,

                'models' => $collection

            ];

        }

        $collection_data = $include_field_info ?

            array(

                'fields' => $this->fields_info(),

                'models' => $collection
            ) :
            $collection;

        return $collection_data;
    }

    /**
     * @return array of fields info
     */
    private function fields_info(){

        return (new Dynamic_Model_Fields_Fetcher( new Dynamic_Model($this->get_model_name()) ))->as_array();

    }

    /**
     * Process a GET request to the API
     * @param $model
     */
    private function process_get( Model_Factory $model = null){

        $tag = new \xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        // If not allowed to read
        if ( ! $this->is_authenticated && ! $this->guest_may_read ){

            $this->result = false;

            $this->error = "You must be logged in to perform this operation";

        }

        // if we have an Id send back a single record

        if ( $this->object_id > 0 ) {

            $object = (new Model($this->dynamic_model_key))->find("$this->keycol='$this->object_id'");

            // If we should fetch field
            if ( $this->command == 'fetch-fields'){

                $fields_fetcher = new Fields_Fetcher( $object );

                $this->api_data['fields'] = $fields_fetcher->as_array();

                if ( $this->object_id == -1 )

                    $this->object = $fields_fetcher;

            }


            if ( $object->exists ) {
                $this->object = $object;

                $this->api_data['operation_result'] = 'success';

            }

            else {

                if ( $this->object_id > 0 ){

                    $this->result = false;

                    $this->error = "No such $this->model_name found with $this->keycol = $this->object_id";

                } else {

                    $this->result = true;
                }


            }

        // otherwise send back a collection of same
        } else {

            // Get a custom field search
            $cSearch = new Custom_Field_Search( $this->model_index+1);

            // If exists
            if ( $cSearch->exists() ){

                $object = (new Dynamic_Model($this->dynamic_model_key))->find("$cSearch->field='$cSearch->value'");

                if ( $object->exists ) {

                    $this->object = $object;

                    $this->api_data['operation_result'] = 'success';

                } else {

                        $this->result = false;

                        $this->error = "No such $this->model_name found with $cSearch->field = $cSearch->value";

                }

            }

            // If we should fetch field
            if ( $this->command == 'fetch-fields'){

                $object = new $this->model_name("$this->keycol='$this->object_id'");


                $fields_fetcher = new Fields_Fetcher( $object );

                $this->api_data['fields'] = $fields_fetcher->as_array();

                if ( $this->object_id == -1 )

                    $this->object = $fields_fetcher;

            } else {


                // Build Query
                $query = (string) new REST_API_Query_for_Model_Factory($this->filters);

                $this->collection_query = (string) new REST_API_Query_for_Model_Factory($this->filters,true);

                /**
                 * We cannot just fetch as an array, we need to fetch them
                 * and then add each one using its method
                 */
                $this->collection = array();

                $models = $model->find_all($query,true,$this->enable_console||$this->debug);

                foreach ( $models as $model) {
                    $key = (new REST_Collection_Model_Key($model))->key();

                    if ( $key)

                        $this->collection[$key] = $model->as_array(array('salt','password'));

                    else

                        $this->collection[] = $model->as_array(array('salt','password'));


                }

                // Get columns
                $this->collection = (new Columns_for_REST_Collection($this->collection))

                    ->as_array();


            }

        }

    }


    /**
     * Process a POST request to create a new Model
     * @param \xo_model $model
     */
    private function process_post($model = null){

        if ( ! $this->is_authenticated && ! $this->allow_guest ){

            $this->result = false;

            $this->error = 'You must be logged in to perform this operation';

            return;

        }

        $debug = false;

        $tag = new \xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        if ( $debug ) echo $tag->format_message("Creating from associative ".new \xo_array($this->data)."<br>\r\n");

        // create a new one from associative array

        $class = $this->model_name;

        // Set current user as created by
        if ( ! isset( $this->data['created_by'] )) $this->data['created_by'] = $this->current_user_id;

        // If debugging
        if ( $this->enable_console ){

            \FB::log("$tag->firebug_format: Processing POST, data appears below");

            \FB::log($this->data);

        }

        // Capture missing fields
        $missing_fields = array();

        $this->object = (new Model($this->dynamic_model_key))->create_from($this->data,$missing_fields);

        // Expose missing fields
        $this->api_data['missing_fields'] = $missing_fields;

        if ( $this->enable_console) {

            \FB::log("$tag->firebug_format: Result Object of type $class from 'Create from Associative' Id is ".$this->object->id);

        }

        // if didn't save properly

        if ( ! $this->object->exists ){

            $this->error = $this->friendly_error($this->object->error());

            if ( $this->session_messages )
            {
                $_SESSION[ $this->session_messages."_message" ] = $this->error;
                $_SESSION[ $this->session_messages."_result" ] = 'error';
            }

            $this->result = false;

        } else {

            $keyfield = $this->object->key_column();

            // reload it
            $search = "$keyfield='" . $this->object->$keyfield . "'";


            $this->object = (new Model(

                isset($_REQUEST['_view']) ? $_REQUEST['_view']:

                $this->dynamic_model_key))->find($search);



            if ( $this->session_messages )
            {
                $_SESSION[ $this->session_messages ."_message"] = $this->error;
                $_SESSION[ $this->session_messages ."_result"] = 'success';
            }

        }

    }

    /**
     * Proces a PUT to update a record
     * @param \xo_model $model
     */
    private function process_put( $model = null ){

        // User must be allowed to update Models
        if ( ! $this->is_authenticated && ! $this->allow_guest ){

            $this->result = false;

            $this->error = 'You must be logged in to perform this operation';

            return;

        }

        // If no Model Id
        if ( ! $this->object_id ){

            // Fetch Ids for action
            $ids = explode(',',$_REQUEST['ids']);

            // Initialize the collection
            $this->collection = array();

            // Get the entities
            $models = $model->fetch_all_from_ids($ids);

            // Set result
            $this->result = true;

            // Set data
            $data = $_REQUEST;

            // Remove ids from data
            unset($data['ids']);

            foreach ( $models as $model){

                // Get the generic model
                $model = new Generic_Model( $model );

                // Delete it
                $result = $model->model()->update_from_associative($data);

                // If unsuccessful
                if ( ! $result ){

                    $this->error = "Error updating model: ".$model->model()->save_error;

                }

                $this->result &=$result;

                $this->collection[] = $model->model()->as_array();

            }


        } else {

            $this->object = $model->find_first("$this->keycol='$this->object_id'");

            if ( ! $this->object || ! $this->object->exists){

                $this->error = "Unable to update Model $this->model_name with $this->keycol = $this->object_id";

                $this->result = false;

            } else {

                // New! Instead of merely updating, can invoke a specific method by name
                // Name can appear in the invoked URL
                if ( $this->uri->part(5) ){

                    $method = $this->uri->part(5);

                    if ( ! method_exists($this->object,$method)){

                        $this->error = "Unable to update Model $this->model_name: $method: Class ".get_class($this->object) ." has no such method.";

                        $this->result = false;


                    } else {

                        $method_result = $this->object->$method();

                        $this->api_data['object_method'] = $method;

                        $this->api_data['method_result'] = $method_result;

                        $this->api_data['operation_result'] = 'success';

                        $this->result = true;


                    }

                }

                if ( ! $this->object->update( $this->data )->put()){

                    $this->error = $this->object->error();

                    $this->api_data['error'] = $this->object->save_error;

                    $this->result = false;

                } else {


                }

            }


        }


    }

    /**
     * Process a delete request
     * @param \xo_model $model
     */
    private function process_delete( $model = null ){

        // User must be authenticated
        if ( ! $this->is_authenticated && ! $this->allow_guest ){

            $this->result = false;

            $this->error = 'You must be logged in to perform this operation';

            return;

        }

        // Special case... no Id, so we're deleting multiple
        if ( $this->object_id <= 0 ){


            $ids = $_REQUEST['ids'];

            if ( strlen( $ids )>0 ){

                // Break out the ids
                $ids = explode(',',$ids);

                // Initialize the collection
                $this->collection = array();

                // Get the entities
                $models = $model->fetch_all_from_ids($ids);

                // Set result
                $this->result = true;

                foreach ( $models as $model){

                    // Get the generic model
                    $model = new Generic_Model( $model );

                    // Delete it
                    $result = $model->model()->delete();

                    // If unsuccessful
                    if ( ! $result ){

                        $this->error = "Error deleting model: ".$model->model()->delete_error;

                    }

                    $this->result &=$result;

                    $this->collection[] = $model->model()->as_array();

                }


            }


        } else {

            $this->object = $model->find_first("$this->keycol='$this->object_id'");

            if ( ! $this->object || ! $this->object->exists){

                $this->error = "Unable to delete Model $this->model_name with $this->keycol = $this->object_id";

                $this->result = false;

            } else {

                if ( ! $this->object->delete( )){

                    $id = $this->object->id();

                    $this->error = "Unable to delete Model $id: ". $this->object->error();

                    $this->result = false;

                } else {

                    $this->api_data['operation_result'] = 'success';
                }

            }

        }


    }

    /**
     * Set some filters to apply when getting stuff
     * @param array $options
     * @return array of filters
     */
    private function set_filters($options){

        // first set from options
        $filters = array_key_exists('filters', $options) ? $options['filters'] : array();

        /**
         * New! If the route includes pagination...
         */
        if ( preg_match('/([a-z_]+)_page([0-9]+)_size([0-9]+)/',$this->uri->part( $this->options['model_index']),$hits)) {

            if($hits[2]!==null) {

                $filters[] = $hits[3]." from page ".$hits[2];

            }
        }

        // Add Get Filters
        $filters = array_merge( $filters, (new GET_Filters())->as_array());


        return $filters;
    }

    // required for abstraction
    public function get_api_result( $key ){ return null; }

    private function friendly_error( $error = '' ){

        return $error;

    }

    private function friendly_model_name(){

        $parts = explode('\\',$this->model_name);

        $parts = count($parts)>1?explode('_',$parts[1]):explode('_',$this->model_name);

        $name = '';

        foreach ( $parts as $part) $name .= $name?" ".ucfirst($part):ucfirst($part);

        return $name;
    }
}
