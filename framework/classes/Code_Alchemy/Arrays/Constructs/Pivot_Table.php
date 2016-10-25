<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/4/15
 * Time: 10:06 AM
 */

namespace Code_Alchemy\Arrays\Constructs;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Model;

/**
 * Class Pivot_Table
 * @package Code_Alchemy\Arrays\Constructs
 *
 * Return an array, which is a Pivot Table of two source tables
 */
class Pivot_Table extends Array_Representable_Object{

    /**
     * Pivot_Table constructor.
     * @param string $source_table_name
     * @param string $columns_table_name
     * @param string $relationship_column_name
     * @param string $pivot_column_name
     * @param string $source_query
     * @param bool|true $skip_empty_rows
     */
    public function __construct(

        $source_table_name,

        $columns_table_name,

        $relationship_column_name,

        $pivot_column_name,

        $pivot_column_label_name,

        $source_query = '',

        $skip_empty_rows = true


    ){

        $pivot_table = array();

        // For each source row
        foreach ( (new Model_Factory($source_table_name))

                      ->find_all($source_query) as $source_row ){

            // Initialize pivot row
            $source_identity = $source_row->$relationship_column_name;

            $pivot_row = array(

                $relationship_column_name => $source_identity

            );

            // Now for each related one
            $columns = (new Model_Factory($columns_table_name))->fetch_all_as_array(

                "$relationship_column_name='$source_identity'"

            );

            foreach ($columns as $column ){


                $pivot_row[ $column[$pivot_column_label_name] ] = $column[$pivot_column_name];

            }

            // Add Pivot row to table
            if ( $skip_empty_rows && ! count( $columns ))

                continue;

            else

                $pivot_table[] = $pivot_row;

        }

        // Send back
        $this->array_values = $pivot_table;
    }

}