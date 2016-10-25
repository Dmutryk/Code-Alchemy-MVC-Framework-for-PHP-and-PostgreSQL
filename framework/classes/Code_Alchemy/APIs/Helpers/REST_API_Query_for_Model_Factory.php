<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 8:46 AM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class REST_API_Query_for_Model_Factory
 * @package Code_Alchemy\APIs\Helpers
 *
 * Build the query string for the Model Factory, for use
 * with a GET request in the REST API
 */
class REST_API_Query_for_Model_Factory extends Stringable_Object{

    /**
     * REST_API_Query_for_Model_Factory constructor.
     * @param array $query_filters_from_api
     * @param bool $exclude_pagination
     */
    public function __construct( array $query_filters_from_api, $exclude_pagination = false ){

        // Get implicit query, if any
        $implicit_query = (string)new Implicit_Name_Column_LIKE_Query();

        $pieces = (new Query_Filters($query_filters_from_api))

            ->as_array();

        if ( $exclude_pagination ) foreach ( $pieces  as $index => $piece )

            if ( in_array($piece,[ 'page_size','page_number']))

                unset($pieces[$index]);

        $query =

            implode(',', $pieces);

        $this->string_representation = $implicit_query? "$implicit_query,$query":$query;

    }

}