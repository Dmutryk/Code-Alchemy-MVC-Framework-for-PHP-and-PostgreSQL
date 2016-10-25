<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/16/15
 * Time: 8:41 PM
 */

namespace Code_Alchemy\Applications\Web_Director;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Model_Hierarchy
 * @package Code_Alchemy\Applications\Web_Director
 *
 * Gets all the Models as a Hierarchy
 */
class Model_Hierarchy extends Array_Representable_Object{

    /**
     * @var bool true to allow local debugging
     */
    private $debug = false;

    /**
     * @param array $settings
     */
    public function __construct( array $settings ){

        if ( $this->debug ){

            \FB::info(get_called_class().": Settings appear next");

            \FB::info($settings);

        }

        $models = array();

        // Prunes are the ones we are going to remove
        $prunes = array();

        // First pass, get all the data structures in place
        foreach ( (new Services_Fetcher($settings))->as_array() as $model )

            // if not Hidden
            if ( ! $model['is_hidden'])

                $models[ $model['table_name'] ] = array_merge(

                    $model,

                    array(

                        'label' => '<a href="'.$model['web_director_url'].'">'.

                            $model['service_label'].'</a>',

                        'children' => array()

                    )

                );

        // Second pass, go through and set up hierarchy
        foreach ( $models as $name => $model ){

            foreach ( $model['parent_tables'] as $parent_table_name )

                if ( isset( $models[$parent_table_name])){

                    $models[$parent_table_name]['children'][] = $model;

                    // As long as Prune doesn't have any children
                    if ( ! count($models[$name]['children']))

                        // Set as a prune
                        $prunes[] = $name;

                }

        }


        /* Check for 3rd level hierarchy

        $temp_models = array();

        foreach ( $models as $name => $model ){

            if ( count($model['children']) ){

                $is_modified = false;

                foreach ( $models as $name2 => $model2 ){

                    if ( $name != $name2 && count($model2['children']) ) {

                        $temp_children = array();

                        foreach ( $model2['children'] as $child ){

                            if ( $child['table_name'] == $model['table_name']){

                                \FB::info("$name is also a child of $name2");

                                // Move it over
                                $temp_children[] = $model;

                                $is_modified = true;


                            } else $temp_children[] = $child;

                        }

                        $model2['children'] = $temp_children;

                    }

                }

            } else $temp_models[] = $model;
        }

        */

        // Last Pass, lose associates
        $adjusted_models = array();

        foreach ( $models as $name => $model)

            if ( ! in_array($name,$prunes ) || count($models[$name]['children']) )

                $adjusted_models[] = $model;

        $this->array_values = $adjusted_models;

    }

}