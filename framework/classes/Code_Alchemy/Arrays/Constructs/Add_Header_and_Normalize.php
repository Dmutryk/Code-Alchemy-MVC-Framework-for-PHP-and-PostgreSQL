<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/4/15
 * Time: 10:37 AM
 */

namespace Code_Alchemy\Arrays\Constructs;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Add_Header_and_Normalize
 * @package Code_Alchemy\Arrays\Constructs
 *
 * Adds a Header row and normalizes, filling in missing values
 */
class Add_Header_and_Normalize extends Array_Representable_Object{

    /**
     * Add_Header_and_Normalize constructor.
     * @param array $original_table
     */
    public function __construct( array $original_table ){

        $new_table = array();

        $header = array();

        foreach ( $original_table as $row )

            foreach ( $row as $column => $value )

                if ( ! in_array($column,$header))

                    $header[] = $column;

        $new_table[] = $header;

        foreach ( $original_table as $row ){

            $normalized_row = array();

            foreach( $header as $head )

                $normalized_row[ $head ] = isset( $row[$head ])?

                    $row[ $head ]:"";

            $new_table[] = $normalized_row;

        }


        $this->array_values = $new_table;


    }

}