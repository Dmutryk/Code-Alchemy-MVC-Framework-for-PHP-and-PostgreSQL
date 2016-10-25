<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Scope_Adder
 * @package Code_Alchemy\Controllers\Helpers
 *
 * Adds Scope values to a search query string
 */
class Scope_Adder extends Stringable_Object{

    /**
     * @param $search_text
     * @param array $scope
     */
    public function __construct( $search_text, array $scope){

        /*
        if ( $this->is_development() ) {

            \FB::info(get_called_class().": Adding Scope to $search_text, current scope appears next");

            \FB::info($scope);

        }*/

        if ( preg_match( '/\$([a-zA-z0-9_]+)\.([a-zA-Z0-9_]+)/',$search_text,$hits)){

            $model = $scope[$hits[1]];

            $regex = '/\$' . $hits[1] . '\.' . $hits[2] . '/';

            $search_text = preg_replace($regex, is_array($model)? $model[$hits[2]]: $model->$hits[2],$search_text);

        }


        // Pass through
        $this->string_representation = $search_text;

        //if ( $this->is_development() ) \FB::info(get_called_class().": result is $search_text");

    }
}