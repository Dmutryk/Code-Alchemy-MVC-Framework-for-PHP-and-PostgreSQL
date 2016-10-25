<?php

// Important!  Change to the namespace of your application
namespace _namespace_\models;
use Code_Alchemy\helpers\File_Uploader;
use Code_Alchemy\components\seo_name;
use Code_Alchemy\parnassus;

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
            <reference_column>title</reference_column>
            <key>id</key>
            <read_only></read_only>
            <required></required>
            <search></search>
            <related>created_by,author</related>
            <intersects>blog_entry_category</intersects>
            <referenced_by>blog_entry_comment</referenced_by>
        </datasource>";

    /**
     * This code will run after you create a new Model of this Class
     * This is the soft equivalent of a database TRIGGER AFTER INSERT
     */
    public function trigger_after_insert(){

    }

    /**
     * Trigger before an update
     */
    public function trigger_before_update(){

        // Get's previous "old" values
        $previous_values = $this->previous_values();

        return true;

    }


    /**
     * @return string Image Filename URL
     */
    public function image_filename_url(){

        return "/images/blog/$this->image_filename";

    }

    /**
     * Update a Model from an Associative Array
     * @param array $array
     * @param int $updating_user_id
     * @return bool
     */
    public function update_from_associative( array $array, $updating_user_id = 0 ){

        // uncomment the below to Handle file upload
        new File_Uploader('image_filename','/images/blog/',$array);

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
        new File_Uploader('image_filename','/images/blog/',$array);

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

        return array_merge( parent::as_array($associative,$exclusions),array(

            // define your custom members here
            'blog_type'=>$this->blog_type(),
            'author'=>$this->author(),
            'post_date'=> $this->post_date(),
            'fontawesome_class'=> $this->fontawesome_class(),
            'categories'=> $this->categories(),
            'flat_categories' => implode(',',$this->categories()),
            'tags'=>$this->tags(),
            'comments'=> $this->blog_entry_comments(),
            'blog_entry_url'=> '/blog/'.$this->id."/". $this->seo_title(),
            'filter_classes'=>$this->filter_classes(),
            'author_url'=> $this->author_url(),
            'preview_text'=>substr($this->text,0,500). "..."

        ));

    }

    /**
     * @return string Author URL
     */
    public function author_url(){

        return '/blog/'.$this->created_by."/".new seo_name($this->created_by()->full_name());

    }
    /**
     * @return array of Blog entry categories
     */
    public function blog_entry_categories(){

        return blog_entry_category::model()->find_all_undeleted("blog_entry_id='$this->id'");

    }

    /**
     * @return string Filter Classes
     */
    public function filter_classes(){

        $classes = '';

        foreach ( $this->blog_entry_categories() as $cat )

            $classes .= ' filter-'.$cat->seo_name();

        return $classes;

    }

    /**
     * @return string
     */
    public function seo_title(){

        return (string) new seo_name($this->title);

    }

    /**
     * @return array of Tags
     */
    public function tags(){

        static $tags = array();

        if ( ! count($tags) )

            foreach( blog_entry_tag::model()->find_all_undeleted("blog_entry_id='$this->id'") as $tag )

                $tags[] = $tag->tag()->name;

        return $tags;

    }


    /**
     * @return array of Categories
     */
    public function categories(){

        static $cats = array();

        if ( ! count($cats) )

            foreach( blog_entry_category::model()->find_all_undeleted("blog_entry_id='$this->id'") as $cat )

                $cats[] = $cat->blog_category()->name;

        return $cats;

    }
    /**
     * @return string FontAwesome Class
     */
    public function fontawesome_class(){

        $lookup = array(
            'image'=>'fa-camera'
        );

        return $lookup[ $this->blog_type() ];

    }

    /**
     * @return string Post Date
     */
    public function post_date(){

        $date = date('j F,Y',strtotime($this->created_date));

        $language = (string) parnassus::instance()->configuration()->language;

        \FB::log($language);

        if ( $language == 'es'){

            setlocale(LC_ALL, 'es_CO.utf8'); // substitute your locale if not es_ES

            $date = strftime("%d de %B %Y", strtotime($this->created_date)); // substitute your date field name
        }

        return $date;
    }

    /**
     * @return string Author of Blog Entry
     */
    public function author(){

       // \FB::log($this->created_by());


        return $this->created_by()->first_name . " ". $this->created_by()->last_name;

    }

    /**
     * @return string Type of Blog Entry
     */
    public function blog_type(){

        $type = 'text';

        // if image
        $ext = (string) new \file_extension_for($this->image_filename);

        if ( in_array(strtolower($ext),array('jpg','jpeg','gif','tif','png')))

            $type = 'image';

        return $type;
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