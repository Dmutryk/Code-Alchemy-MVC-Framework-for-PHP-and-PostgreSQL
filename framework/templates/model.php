<?php

// Important!  Change to the namespace of your application
namespace _namespace_;

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
			<key>id</key>
			<read_only></read_only>
			<required></required>
			<search>type</search>
		</datasource>";

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
     * BEGIN DO NOT MODIFY ANYTHING BETWEEN THESE COMMENTS!
     */
    const bo_token = "<bo-business_object_template>";
    const bo_token_end = "</bo-business_object_template>";

    private static $xml_obj = null,$xml_src_obj = null;
    // constructor
    public function __construct( $search = null) {
        if ( ! self::$xml_obj) self::$xml_obj = simplexml_load_string(
            self::bo_token.self::datasource.self::bo_token_end
        );
        $this->xml_obj = self::$xml_obj;
        parent::__construct( get_class(), $search );
    }
    public static function source() {
        if ( ! self::$xml_src_obj)
            self::$xml_src_obj = simplexml_load_string( self::datasource );
        return \DataSource2::get_source( self::$xml_src_obj);
    }
    /**
     * END DO NOT MODIFY ANYTHING BETWEEN THESE COMMENTS!
     */

}
?>