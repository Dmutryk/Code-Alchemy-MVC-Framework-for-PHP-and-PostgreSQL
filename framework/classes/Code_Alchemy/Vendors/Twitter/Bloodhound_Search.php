<?php


namespace Code_Alchemy\Vendors\Twitter;

use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Model_Configuration;

/**
 * Class Bloodhound_Search
 * @package Code_Alchemy\Vendors\Twitter
 *
 * Back-end companion to bloodhound.js, as used with Twitter Typeahead.js
 *
 */
class Bloodhound_Search extends Array_Representable_Object {

    /**
     * @param $model_name
     * @param $query
     * @param array $search_filters
     */
    public function __construct( $model_name, $query, array $search_filters = array() ){

        $filters = count( $search_filters )? implode(',',$search_filters).",":'';
        if ( $query )

            $this->array_values = (new Model_Factory($model_name))

                ->all_undeleted_as_array(

                    $filters.

                    (new Model_Configuration())

                        ->model_for($model_name)['reference_column']." LIKE %$query%");

    }

}