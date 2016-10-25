<?php


namespace Code_Alchemy\Models;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Controllers\Helpers\As_Array_Classes_Directory;
use Code_Alchemy\Controllers\Helpers\As_Array_Member_Classname;
use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\SQL\Delete_SQL;
use Code_Alchemy\Database\SQL\Update_SQL;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\Database\Table\Table_Column_Names;
use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Helpers\Current_User_Id;
use Code_Alchemy\helpers\File_Uploader;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Models\Components\Model_Settings;
use Code_Alchemy\Models\Factories\Model_Cloner;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\components\seo_name;
use Code_Alchemy\Models\Filters\Error_Filter;
use Code_Alchemy\Models\Helpers\As_Array_Pre_Filter;
use Code_Alchemy\Models\Helpers\Custom_As_Array_Pre_Filter;
use Code_Alchemy\Models\Helpers\Custom_Methods_Classname_For;
use Code_Alchemy\Models\Helpers\Custom_Model_Constructor_Classname;
use Code_Alchemy\Models\Helpers\Custom_Pre_Filter_Classname_For;
use Code_Alchemy\Models\Helpers\Error_Filter_Classname_For;
use Code_Alchemy\Models\Helpers\Unique_Object_Id;
use Code_Alchemy\Models\Interfaces\Model_Interface;
use Code_Alchemy\Models\Triggers\After_Delete;
use Code_Alchemy\Models\Triggers\After_Insert;
use Code_Alchemy\Models\Triggers\After_Update;
use Code_Alchemy\Models\Triggers\Automated\Automated_After_Insert_Trigger;
use Code_Alchemy\Models\Triggers\Before_Insert;
use Code_Alchemy\Models\Triggers\Before_Update;
use Code_Alchemy\Models\Validation\Members_Validation;

class Dynamic_Model extends Array_Representable_Object implements Model_Interface{

    /**
     * @var string The Model Key
     */
    private $model_key = '';

    /**
     * @var bool true if model exists in database
     */
    public $exists = false;

    private static $fast_cache = null;

    /**
     * @var array of previous values, before an update
     */
    private $previous_values = array();

    /**
     * @var array of missing fields
     */
    private $missing_fields = array();

    /**
     * @param $model_key
     * @param array $seed_values
     */
    public function __construct( $model_key, array $seed_values = array() ){

        // Initialize Fast Cache
        if ( ! self::$fast_cache ) self::$fast_cache = new Fast_Cache(100);

        // Set up a pre-hook
        $this->set_pre_hook('as_array_prefilter');

        $this->model_key = $model_key;

        // Plant seeds...
        $this->array_values = $seed_values;

        if ( count( $seed_values) && isset( $seed_values[$this->key_column()])){

            // Adjust values
            $this->adjust_values();

            // Get As Array members
            $this->get_as_array_members();

            $this->exists = true;

        }

        // Now attach custom model constructor, if available
        $this->attach_custom_model_constructor();

    }

    /**
     * Clone a Model
     * @param array $overrides allow values to be explicitly set in the Clone
     * @return Model
     */
    public function _clone( array $overrides = array() ){

        return (new Model_Cloner( $this, $overrides ))->get_clone();

    }

    /**
     * @param array $exclusions
     */
    protected function as_array_prefilter( array $exclusions ){

        $model_configuration = (new Model_Configuration())->model_for($this->model_key);

        // only if user didn't ask to omit pre filter

        if ( ! isset( $model_configuration['omit_array_prefilter']) || ! $model_configuration['omit_array_prefilter'])

            $this->array_values = (new As_Array_Pre_Filter($this->array_values,$this->model_key, $model_configuration))->as_array();

        // Now apply a Custom Pre-filter
        $custom_prefilter_class = (string) new Custom_Pre_Filter_Classname_For($this->model_key);

        // If filter doesn't exist
        if ( ! class_exists( $custom_prefilter_class ) && $this->is_development() ){

            global $webapp_location;

            // Copy it over
            $camelCase_Name = new CamelCase_Name($this->model_key, '_', '_');

            $copier = new Smart_File_Copier(new Code_Alchemy_Root_Path()."/templates/classes/Custom_As_Array_Pre_Filter.php",
            "$webapp_location/app/classes/".new Namespace_Guess()."/Models/As_Array_Pre_Filters/". $camelCase_Name.".php",
                array(
                    '/__app_namespace__/'=>(string) new Namespace_Guess,
                    '/__custom_prefilter_name__/'=>$camelCase_Name,

                ),
                false
            );

            if ( $copier->copy() && $this->is_development())

                \FB::info(get_called_class().": $camelCase_Name: Custom Prefilter was created");


        }

        // Finally run filter
        if ( class_exists($custom_prefilter_class) && is_subclass_of($custom_prefilter_class,"\\Code_Alchemy\\Models\\Helpers\\Custom_As_Array_Pre_Filter"))

            $filter = new $custom_prefilter_class( $this->array_values, $this, new Array_Object( $this->array_values ) );


    }

    /**
     * Attach a Custom Model Constructor
     */
    private function attach_custom_model_constructor(){

        global $autoload_bypass_exception;

        // Suppress error for class not found
        $autoload_bypass_exception = true;

        // Flag to indicate if save is p
        $do_put = false;

        // Get class
        $class = (string) new Custom_Model_Constructor_Classname($this->model_key);

        if ( class_exists( $class )){

            // resume error for class not found
            $autoload_bypass_exception = false;

            if ( is_subclass_of($class,"\\Code_Alchemy\\Models\\Constructors\\Custom_Model_Constructor"))

                $constructor = new $class( $this->array_values, $do_put );

            else

                \FB::warn(get_called_class().":$class: Must be a subclass of Custom_Model_Constructor");


        }



        if ( $do_put )

            $this->put();

    }

    /**
     * @param $member_name
     * @return null
     */
    public function get($member_name){

        return isset($this->array_values[$member_name])? $this->array_values[$member_name]:null;

    }

    /**
     * @param $member
     * @param $value
     * @return $this
     */
    public function set($member, $value ){

        $this->array_values[$member] = $value;

        return $this;
    }
    /**
     * Get any custom As Array members
     */
    private function get_as_array_members(){

        $classes_directory = (string)new As_Array_Classes_Directory($this->model_key);

        if ( is_dir($classes_directory))

            foreach ((new Directory_API($classes_directory))->directory_listing() as $file){

                $class = (string) new As_Array_Member_Classname($file,$this->model_key);

                if ( class_exists($class))

                    $this->array_values[(string)new \file_basename_for($file)] = (string) new $class($this);
            }


    }

    /**
     * Adjust values as required
     */
    private function adjust_values(){

        // If no SEO name, and model is updateable
        if ( $this->is_updateable() &&

            $this->has_column('seo_name') && ( $this->array_values['seo_name'] != $this->seo_name())){

            $this->array_values['seo_name'] = $this->seo_name();

            $this->put('Update to refresh SEO name');
        }

        // Is a Unique Object Id used but not present
        if ( $this->has_column('unique_object_id') && ! $this->array_values['unique_object_id']){

            // Set a Unique Object Id
            $this->array_values['unique_object_id'] = (string) new Unique_Object_Id();

            $this->put('Setting Unique Object Id');

        }



    }

    /**
     * @param $name
     * @return bool
     */
    public function has_column( $name ){

        return in_array($name,$this->columns());

    }

    /**
     * @return string SEO name
     */
    public function seo_name(){

        $seo_name = new seo_name(trim(@$this->array_values[$this->reference_column()]));

        return (string)$seo_name;
    }

    public function referenced_by(){

        return array();

    }

    /**
     * @return string Key Column
     */
    public function key_column(){

        return (string) @(new Model_Configuration())->model_for($this->model_key)['key_column'];
    }

    /**
     * @return string Table Name
     */
    public function table_name(){

        $model_for = (new Model_Configuration())->model_for($this->model_key);

        return (string) isset( $model_for['table_name'] ) ? $model_for['table_name']: "$this->model_key";


    }

    /**
     * @return null
     */
    public static function source() {

        return null;

    }

    /**
     * @return array of intersections
     */
    public function intersections(){

        $isects = array();

        $values = @(new Model_Configuration())->model_for($this->model_key)['intersections'];

        if ($values ) $isects= $values;

        return $isects;

    }

    /**
     * @return array of related objects
     */
    public function related_objects(){

        $related = array();

        $values = @(new Model_Configuration())->model_for($this->model_key)['related'];

        if ( $values) $related = $values;

        return $related;

    }


    /**
     * Get the Model Factory for this Model
     * @param bool $list_view_if_available if true then use an alternate list view, when configured
    * @param string $model_name to return it
     * @return Model_Factory
     */
    public function get_factory( $list_view_if_available = false, &$model_name = '' ){

        $model_configuration = $this->model_configuration();

        $list_view = @$model_configuration['list_view'];

        $model_name =  $list_view_if_available && $list_view  ? $list_view : $this->model_key;

        return new Model_Factory( $model_name );

    }

    public function factory(){

        return $this->get_factory();
    }

    /**
     * @return bool true if table is to be created
     */
    public function auto_create_table(){

        $model_for = (new Model_Configuration())->model_for($this->model_key);

        return !! ( isset( $model_for['create_table'] ) && $model_for['create_table']);

    }

    /**
     * @return array of references
     */
    public function references(){

        return (array) (new Model_Configuration())->model_for($this->model_key)['references'];

    }

    /**
     * @return array of table options
     */
    public function table_options(){


        return array(
            'references'=>array_shift($this->references())
        );

    }
    /**
     * @return string Model template
     */
    public function model_template(){

        $model_template = (new Model_Configuration())->model_for($this->model_key)['model_template'];

        //\FB::info($model_template);

        return (string)$model_template;


    }

    /**
     * @return string Model reference column
     */
    public function reference_column(){

        $model_descr = (new Model_Configuration())->model_for($this->model_key);

        return (string) @$model_descr['reference_column'];


    }

    /**
     * @return array of columns
     */
    public function columns(){

        $columns = (new Model_Settings($this->model_name()))->columns;

        return
            $columns && is_array($columns) && count( $columns) ?

                $columns :

            (new Table_Column_Names($this->table_name()))->as_array();

    }

    /**
     * @param $field_name
     * @return bool true if field is required
     */
    public function is_field_required( $field_name  ){

        return false;
    }

    /**
     * @return array model configuration
     */
    public function model_configuration(){

        return (new Model_Configuration())->model_for($this->model_key);

    }

    /**
     * @param array $members
     * @return array of memebrs
     */
    public function members_as_array( array $members ){

        $result = array();

        foreach( $members as $member ){

            $value = $this->$member;

            if ( $value )

                $result[$member] = $this->$member;

        }


        return $result;
    }

    /**
     * @return string Model Name
     */
    public function model_name(){

        return $this->model_key;

    }

    /**
     * @return array|null Cloning rules
     */
    public function cloning_rules(){

        return isset( $this->model_configuration()['cloning_rules'])?

            $this->model_configuration()['cloning_rules']: null;
    }

    /**
     * @return array fo required fields
     */
    public function required_fields(){

        $model_for = (new Model_Configuration())->model_for($this->model_key);

        return isset( $model_for['required'])? $model_for['required']:array();


    }


    /**
     * @param $what
     * @return null
     */
    public function __get( $what ){

        return isset($this->array_values[$what])? $this->array_values[$what]:null;
    }

    /**
     * @param string $reason
     * @param array $missing_fields
     * @param bool|false $echo_back_sql to screen, for debugging
     * @return bool
     */
    public function put(

        $reason = '',
        array &$missing_fields = array(),
        $echo_back_sql = false

    ){

        //if ( $this->is_development() && $reason) \FB::info(get_called_class().":put($reason)");

        $members_Validation = (new Members_Validation($this->array_values, $this->required_fields(),$missing_fields));

        if ( ! $members_Validation->is_valid() ){

            if ( $this->is_development() ) \FB::warn(get_called_class().": Validation error $members_Validation->error");

            $this->_error = $this->filter_error( $members_Validation->error );

            $this->missing_fields = $missing_fields;

            return false;

        }

        $database = (new Database());

        $update_SQL = (string)new Update_SQL(
            $this->table_name(),
            $this->columns(),
            $this->array_values,
            $this->key_column(),
            $this->array_values[$this->key_column()],
            $echo_back_sql
        );


        $is_error = false;

        $db_result = $database->query($update_SQL,$is_error);

        // Workaround to avoid call by reference
        $result = $is_error;

        // Trigger after update
        $this->hook_trigger_after_update( ! $result );

        $this->_error = $this->filter_error( $database->error() );

        return ! $result;

    }

    /**
     * @return array of missing fields
     */
    public function missing_fields(){ return $this->missing_fields; }

    private function handle_file_uploads( array &$values ){

        // For file uploads
        foreach ( $_FILES as $name=>$value ){

            // if image file
            if ( preg_match('/(.+_)?image_filename/',$name,$hits))

                new File_Uploader(@$hits[1]."image_filename",'/images/'.$this->model_key,$values);

            // if PDF file
            if ( preg_match('/(.+_)?pdf_filename/',$name,$hits))

                new File_Uploader(@$hits[1]."pdf_filename",'/images/'.$this->model_key,$values,\file_upload::ALLOW_ALL);


        }

    }

    /**
     * @param array $values
     * @return bool true if should create model
     */
    private function just_before_creating( array &$values ){

        // By default, we allow the record to be inserted
        $insert_error = '';

        $this->handle_file_uploads($values);

        // Trigger before Insert
        $values = (new Before_Insert($this->model_key,$values,$insert_error))->as_array();

        // Pass error through to Model
        if ( $insert_error ) $this->_error = $insert_error;

        // Success if no error
        return strlen( $insert_error )== 0;

    }

    /**
     * Create a new Model from an array of values
     * @param array $values
     * @param array $missing_fields allows you to receive missing fields
     * @return $this
     */
    public function create_from( array $values, array &$missing_fields = array() ){

        $threadId = (string) new Random_Password(10);

       // Just before creating
        if ( $this->just_before_creating( $values )) {

            $members_Validation = (new Members_Validation($values, $this->required_fields(),$missing_fields));

            if ( ! $members_Validation->is_valid() ){

                $this->_error = $this->filter_error( $members_Validation->error );

                $this->missing_fields = $missing_fields;

                return $this;

            }


            $database = (new Database());

            $table_name = $this->table_name();

            //\FB::info(get_called_class().": $threadId: Table name for save is $table_name");

            $persisted_values = $database->save_to_table($table_name,$values);
            // if saved
            if ( count($persisted_values) && isset( $persisted_values[$this->key_column()])){

                // update them here
                $this->array_values = $persisted_values;

                $this->exists = true;

            } else{

                $this->exists = false;

                $this->_error = $this->filter_error( $database->error() );

                \FB::error(get_called_class().": Unable to create new Model $this->model_key: $this->_error");

                // Bubble up
                $this->array_values['error'] = $this->_error;

            }

            // Hook for trigger after insert
            $this->hook_trigger_after_insert( $values);



        } else {

            //\FB::info(get_called_class().": $threadId: Just before creating failed");

        }



        return $this;
    }

    /**
     * Allow user to filter error text
     * @param string $error_text
     * @return string error after being filtered
     */
    private function filter_error( $error_text ){

        // First use the internal filter
        $error_text = (string) new Error_Filter($error_text);

        // Get error filter class
        $classname = (string) new Error_Filter_Classname_For( $this->model_key );

        $camelcase_name = new CamelCase_Name($this->model_key,'_','_');

        // if doesn't exist
        if ( $this->is_development() && $classname && ! class_exists( $classname) ){

            global $webapp_location;

            // Copy it over
            $copier = new Smart_File_Copier(

                new Code_Alchemy_Root_Path()."/templates/classes/Error_Filter.php",

                "$webapp_location/app/classes/".new Namespace_Guess()."/Models/Error_Filters/". $camelcase_name.".php",

                array(

                    '/__namespace__/'=>(string) new Namespace_Guess,

                    '/__classname__/'=>$camelcase_name,

                ),

                false
            );

            if ( $copier->copy() )

                \FB::info(get_called_class().": $camelcase_name: Error Filter component was created");


        }

        if ( class_exists( $classname ) )

            $error_text = (string) new $classname( $error_text, $this->array_values );

        return $error_text;

    }

    /**
     * @param array $values
     */
    public function seed_from( array $values ){

        $this->array_values = $values;

        $this->exists = true;

        return $this;
    }

    /**
     * Hook the after-insert trigger
     */
    private function hook_trigger_after_insert( array $assertions ){

        // By default no changes
        $is_changed = false;

        // Hook automated trigger from configuration
        $this->array_values = (new Automated_After_Insert_Trigger($this->model_name(),$this->array_values,$assertions))->as_array();

        // Run through trigger
        $this->array_values = (new After_Insert($this->model_key, $this->array_values, $assertions, $is_changed))->as_array();

       // print_r($this->array_values); exit();

        // if changed
        if ( $is_changed )

            $this->put('Changes from After Insert Trigger');

    }

    /**
     * @param bool $update_result
     */
    private function hook_trigger_after_update( $update_result ){

        new After_Update(
            $this->model_key,
            $this->array_values,
            $this->previous_values,
            $update_result);

    }

    /**
     * @return string error
     */
    public function error(){

        return $this->_error;

    }

    /**
     * @param $query
     * @param string $comma_substitute
     * @param bool|false $debug
     * @param bool|true $use_cache
     * @return $this
     */
    public function find(

        $query,

        $comma_substitute = '',

        $debug = false,

        $use_cache = true
    ){

        // If not a valid search, just skip it...
        if ( preg_match('/([a-zA-Z0-9_]+)=\'\'/',$query)) {

            //if ( $this->is_development() ) \FB::warn(get_called_class().":$query: Not a valid search query");
            return $this;
        }

        // get a cache key
        $key_construct = $this->model_key . "-" . $query;

        $cache_key = (string) new Cache_Key($key_construct);

        // Get DB
        $database = (new Database());

        // is this search in the cache?
        if ( $use_cache && self::$fast_cache->exists( $cache_key )){

//            if ( $this->is_development() ) \FB::info(get_called_class().":$key_construct: Already in Cache");

            // if so, just use it
            $persisted_values = self::$fast_cache->get( $cache_key );



        }

        else {

            //\FB::info("query in Dynamic_Model.php:");
            //\FB::info($query);
            // Load from db
            $persisted_values =

                $database->load_from_table(
                    $this->table_name(),
                    $query,
                    $comma_substitute,
                    $debug
                );

            // Save to cache for reuse
            self::$fast_cache->set( $cache_key, $persisted_values );

        }

        // if found
        if ( count($persisted_values) && isset( $persisted_values[$this->key_column()])){

            // update them here
            $this->array_values = $persisted_values;

            $this->exists = true;

            // Adjust values
            $this->adjust_values();

            // Get As Array members
            $this->get_as_array_members();



        } else

            $this->_error = $this->filter_error( $database->error() );

        return $this;


    }

    /**
     * @param array $changes
     * @return $this
     */
    public function update( array $changes ){


        // Apply trigger
        $changes = (new Before_Update($this->model_key,$changes,$this->array_values))->as_array();

        // For file uploads
        $this->handle_file_uploads($changes);

        //if ( $this->is_development() ) \FB::info(get_called_class().": Changes are ".new \xo_array($changes));

        // Save previous values
        $this->previous_values = $this->array_values;

        foreach ( $changes as $name=>$value )

            $this->array_values[$name]= $value;

        //if ( $this->is_development() ) \FB::info(get_called_class().": Values after changes are ".new \xo_array($this->array_values));

        return $this;

    }

    /**
     * @param bool|false $dont_safe_delete
     * @return bool true if deleted
     */
    public function delete( $dont_safe_delete = false ){



        // If has is_deleted flag
        if ( ! $dont_safe_delete && (new Database_Table($this->table_name()))->supports_safe_delete() ) {

            $delete = $this->update(array(

                'is_deleted' => true,

                'deleted_date' => date('Y-m-d H:i:s'),

                'deleted_by' => (string)new Current_User_Id()

            ))->put();

            // If successful
            if ( $delete )

                // Fire trigger
                new After_Delete($this->model_key,$this->as_array());

            return (bool)$delete;
        }

        else {

            $database = (new Database);

            $query = (bool)$database->query((string)new Delete_SQL($this->table_name(), $this->key_column(), $this->array_values[$this->key_column()]));

            if ( $database->error() ){

                $query = false;

                $this->_error = $this->filter_error($database->error());

            }



            return $query;
        }

    }

    /**
     * Call a Custom Method
     * @param $method_name
     * @param $args
     * @param bool $get_as_array
     * @return null
     */
    public function custom_method( $method_name, $args, $get_as_array = true ){

        //if ( $this->is_development() ) \FB::info(get_called_class().": Custom Method `$method_name` was called for Model $this->model_key");

        $result = null;

        $camelCase_Name = new CamelCase_Name($this->model_key, '_', '_');

        // Get Custom Methods classname
        $custom_methods_classname = (string) new Custom_Methods_Classname_For($this->model_key);

        // if doesn't exist
        if (  $this->is_development() && $custom_methods_classname && ! class_exists( $custom_methods_classname ) ){

            global $webapp_location;

            // Copy it over
            $copier = new Smart_File_Copier(new Code_Alchemy_Root_Path()."/templates/classes/Model_Custom_Methods.php",
                "$webapp_location/app/classes/".new Namespace_Guess()."/Models/Custom_Methods/". $camelCase_Name.".php",
                array(
                    '/__app_namespace__/'=>(string) new Namespace_Guess,
                    '/__custom_methods_name__/'=>$camelCase_Name,

                ),
                false
            );

            if ( $copier->copy() )

                \FB::info(get_called_class().": $camelCase_Name: Custom Methods component was created");


        }

        // Now if exists
        if ( class_exists($custom_methods_classname)){

            // User may opt out of As array, to avoid infinite recursion
            $values = $get_as_array ?  $this->as_array(): $this->array_values;

            // Invoke it within given name
            $custom_methods = new $custom_methods_classname( $values );

            $result = $custom_methods->call_method( $method_name, $args, $values, $this->_error );

        }




        return $result;
    }

    /**
     * Allows users to attach Custom Methods
     * @param $custom_method
     * @param $args
     * @return null
     */
    public function __call( $custom_method, $args ){

        return $this->custom_method( $custom_method, $args, true);
    }

    /**
     * @return mixed reference value
     */
    public function reference_value(){

        //\FB::info(get_called_class().": Obtaining reference value for $this->model_key");

        $reference_column = $this->reference_column();

        $reference_value = $this->get($reference_column);


        if ( ! $reference_value )

            $reference_value = $this->custom_method($reference_column,array(),false);

        return $reference_value;

    }

    /**
     * @return string representation of Model
     */
    public function __toString(){

        return (string) new Array_Object($this->as_array());

    }

    /**
     * @return int Id of model
     */
    public function id(){

        $member = $this->key_column();

        return (int) $this->$member;

    }

    /**
     * Invalidate the entire Cache
     */
    public static function invalidate_cache(){

        if ( self::$fast_cache)  self::$fast_cache->invalidate_cache();

    }

    /**
     * @param string $type to check
     * @param string $member to check
     * @return bool true if member is type
     */
    public function is( $type, $member = 'type' ){

        return !! ($this->$member == $type);

    }

    /**
     * @return bool true if model is updateable
     */
    public function is_updateable(){

        $model_configuration = $this->model_configuration();

        return ! (

            isset( $model_configuration['is_updateable'] ) &&

            ! $this->model_configuration()['is_updateable']

        );
    }

}
