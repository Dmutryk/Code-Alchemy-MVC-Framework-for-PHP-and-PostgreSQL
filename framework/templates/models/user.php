<?php

// Important!  Change to the namespace of your application
namespace _namespace_\models;
use Code_Alchemy\helpers\File_Uploader;

/**
 * Project:     Your Project Name
 * Model:       Your Module Name and Description
 *
 * Purpose:     Desscribe the purpose of this Model
 *
 */
class _model_name_ extends \business_object {

    /**
     * Specify the data source details for this type of Business Object
     * name: name of Table or View
     * type: table or view
     * alias: for now, same as name
     * key: the name of the primary key field
     * read_only: comma-separated list of read-only columns
     * required: comma-separated list of required columns
     * search: comma-separated list of searchable fields
     */
    const datasource =
        "<datasource>
            <name>_table_name_</name>
            <type>table</type>
            <alias>_table_name_</alias>
            <reference_column>name</reference_column>
            <key>id</key>
            <read_only></read_only>
            <required></required>
            <search></search>
            <related></related>
        </datasource>";

    /**
     * Trigger before an update
     */
    public function trigger_before_update(){

        // Get's previous "old" values
        $previous_values = $this->previous_values();

        return true;

    }


    /**
     * This code will run after you create a new Model of this Class
     * This is the soft equivalent of a database TRIGGER AFTER INSERT
     */
    public function trigger_after_insert(){

    }

    /**
     * @return string Image Filename URL
     */
    public function image_filename_url(){

        return "/images/$this->image_filename";

    }

    /**
     * Update a Model from an Associative Array
     * @param array $array
     * @param int $updating_user_id
     * @return bool
     */
    public function update_from_associative( array $array, $updating_user_id = 0 ){

        // uncomment the below to Handle file upload
        //new File_Uploader('image_filename','/images/portfolio/',$array);

        return parent::update_from_associative( array_merge( $array, array(

            // put your customizations here
        )), $updating_user_id );

    }

    /**
     * Create a new Model from an Associative Array
     * @param array $array
     * @param \xo_loggable $logger
     * @return object
     */
    public static function create_from_associative( array $array, \xo_loggable $logger = null ){

        // uncomment the below to Handle file upload
        //new File_Uploader('image_filename','/images/portfolio/',$array);

        return parent::create_from_associative(array_merge( $array , array(

            // put your custom modifications here

        )), $logger );

    }

    /**
     * Fetch the Model as an array
     * @param bool $associative true to send back an associative array
     * @param array $exclusions to not include from original members
     * @return array representation
     */
    public function as_array( $associative = true , array $exclusions = array() ){

        if ( ! $this->sortable_id ){

            $this->sortable_id = $this->id;

            $this->save();

        }
        return array_merge( parent::as_array($associative,$exclusions),array(

            // define your custom members here

        ));

    }

    /**
     * @return bool true if deleted
     *
     */
    public function delete(){

        /**
         * Perform your custom actions here
         */

        return parent::delete();

    }

    /**
     * Safely delete this Model, that is mark it as deleted
     * @param int $deletor
     * @return bool true if safely deleted
     */
    public function safe_delete( $deletor = 0 ){

        /**
         * Per4m your custom actions here
         */

        return parent::safe_delete( $deletor );

    }

    /**
     * In general, you do not need to change this method, except in cases
     * where you need to transform the data from the database, such as to
     * unserialize() arrays, etc, or other complex operations
     * @param string $what the name of the member
     * @return array|bool|\human_time|mixed|null|string
     */
    public function __get( $what ) {
        global $container,$webroot;
        $tag = new \xo_codetag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        switch( $what ){
            default:
                return parent::__get( $what );
                break;
        }
    }

    /**
     * In general, you do not need to modify this method, except in cases where it
     * is necessary to transform data, prior to saving it, such as serialize()
     * urlencode() or other filters, or complex operations
     * @param string $what to save
     * @param mixed $how to save it
     */
    public function __set( $what, $how ){
        switch( $what){
            default:
                parent::__set( $what, $how);
                break;
        }
    }

    /**
     * DO NOT MODIFY ***ANYTHING*** BETWEEN THESE COMMENTS!!!!!!!!
     */
    const bo_token="<bo-business_object_template>";const bo_token_end="</bo-business_object_template>";
    private static $xml_obj=null,$xml_src_obj=null;public function __construct( $search = null) {
    if(!self::$xml_obj)self::$xml_obj = simplexml_load_string(self::bo_token.self::datasource.self::bo_token_end);
    $this->xml_obj = self::$xml_obj;parent::__construct(get_class(),$search);}public static function source() {
    if(!self::$xml_src_obj)self::$xml_src_obj=simplexml_load_string(self::datasource);
    return \DataSource2::get_source( self::$xml_src_obj);}
    /**
     * DO NOT MODIFY ANYTHING BETWEEN THESE COMMENTS!
     */

}
?>