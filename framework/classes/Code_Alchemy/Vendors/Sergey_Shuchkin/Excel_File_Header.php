<?php


namespace Code_Alchemy\Vendors\Sergey_Shuchkin;


use Code_Alchemy\Arrays\Array_Real_Values;
use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Excel_File_Header
 * @package Code_Alchemy\Vendors\Sergey_Shuchkin
 *
 * Gets the header row of an Excel File
 */
class Excel_File_Header extends Array_Representable_Object {

    public function __construct( $file_path ){

        $xlsx = new SimpleXLSX($file_path);

        $rows = $xlsx->get_rows();

        $this->array_values = (new Array_Real_Values(isset($rows[0])?$rows[0]:array()))->as_array();

    }

}