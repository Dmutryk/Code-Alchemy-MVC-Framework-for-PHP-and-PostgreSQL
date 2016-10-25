<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/19/15
 * Time: 10:20 AM
 */

namespace Code_Alchemy\Data_Management;


use Code_Alchemy\Arrays\Impose_Association_on_Array;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Vendors\Sergey_Shuchkin\SimpleXLSX;

/**
 * Class Importable_Excel_File_Rows
 * @package Code_Alchemy\Data_Management
 *
 * Transforms Excel document rows into an importable format
 */
class Importable_Excel_File_Rows extends Array_Representable_Object{

    /**
     * Importable_Excel_File_Rows constructor.
     * @param $file_full_path
     */
    public function __construct( $file_full_path ) {

        $header = array();

        $rows = (new SimpleXLSX($file_full_path))->get_rows();

        $importable = array();

        foreach( $rows as $row ){

            if ( ! count( $header) )

                $header = $row;

            else {

                $importable[] = (new Impose_Association_on_Array($row,$header))->as_array();
            }
        }
        $this->array_values = $importable;

    }
}