<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/28/16
 * Time: 12:03 PM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Query_For_REST_Count_Total
 * @package Code_Alchemy\APIs\Helpers
 *
 * Gets the Query string for the REST count total
 *
 */
class Query_For_REST_Count_Total extends Stringable_Object{

    /**
     * Query_For_REST_Count_Total constructor.
     * @param array $filters
     */
    public function __construct( array $filters ) {

        $query = '';

        $applied_filters = [];

        if ( isset( $_REQUEST['_filter_total'])){

            $filter_total = $_REQUEST['_filter_total'];

            $items = explode(',', $filter_total);

            foreach ( $items as $item )

                foreach( $filters as $filter)

                if ( preg_match("/$item/",$filter))

                    $query .= $query ? ','. $filter: $filter;


        }


        $this->string_representation = $query;

    }
}