<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/30/15
 * Time: 5:24 PM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Query_Filters
 * @package Code_Alchemy\APIs\Helpers
 *
 * A collection of filters for querying as part of a RESTful request,
 * represented as an object that can be converted into an array
 */
class Query_Filters extends Array_Representable_Object{

    /**
     * @param array $current_filters to process
     */
    public function __construct( array $current_filters ){

        // For is deleted
        if ( isset($_REQUEST['is_deleted']))

            $current_filters[] = "is_deleted='".$_REQUEST['is_deleted']."'";

        // For pagination
        if ( isset( $_REQUEST['page_number']) && isset( $_REQUEST['page_size']))

            $current_filters = array_merge($current_filters,array(

                'LIMIT '.$_REQUEST['page_size'],

                'OFFSET '. (($_REQUEST['page_size'] * $_REQUEST['page_number'])-$_REQUEST['page_size'])

            ));

        $this->array_values = $current_filters;

    }

}