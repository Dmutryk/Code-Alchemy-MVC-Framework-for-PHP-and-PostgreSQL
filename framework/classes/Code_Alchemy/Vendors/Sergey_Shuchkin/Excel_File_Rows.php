<?php


namespace Code_Alchemy\Vendors\Sergey_Shuchkin;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Excel_File_Header
 * @package Code_Alchemy\Vendors\Sergey_Shuchkin
 *
 * Gets the header row of an Excel File
 */
class Excel_File_Rows extends Array_Representable_Object {


    /**
     * @var string Method to use to get Rows
     */
    private $row_method = 'rowsEx';

    public function __construct( $file_path ){

        $method = $this->row_method;

        $xlsx = new SimpleXLSX($file_path);

        $rows = $xlsx->$method();

        $worksheet = 0;

        while ( ! count( $rows) && $worksheet < 5 ) {

            $rows = $xlsx->$method($worksheet++);

        }

        if ( ! count( $rows))

            $this->error = $xlsx->error();


        if ( count( $rows ))

            $this->array_values = $rows;

    }

    /**
     * @return string Error if any
     */
    public function error(){ return $this->error; }

}