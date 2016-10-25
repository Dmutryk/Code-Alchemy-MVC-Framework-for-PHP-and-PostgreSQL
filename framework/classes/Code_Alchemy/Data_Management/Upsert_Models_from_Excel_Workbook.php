<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/15/15
 * Time: 5:18 PM
 */

namespace Code_Alchemy\Data_Management;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Vendors\Sergey_Shuchkin\SimpleXLSX;

/**
 * Class Upsert_Models_from_Excel_Workbook
 * @package Code_Alchemy\Data_Management
 *
 * Upsert Model records from an Excel workbook
 */
class Upsert_Models_from_Excel_Workbook extends Array_Representable_Object{

    /**
     * @param string $excel_file_full_path
     * @param string $model_name
     */
    public function __construct( $excel_file_full_path, $model_name ){

        $workbook = new SimpleXLSX($excel_file_full_path);

        $data = array();

        $rows = $workbook->rows();

        if ( ! count( $rows)) $rows = $workbook->rows(2);

        // For each row of data
        foreach ( $rows as $row )

            if ( ! $this->header_row )

                $this->header_row = $row;

            else {

                $new_data = array();

                foreach ( $this->header_row as $header ){

                    $value = $row[array_search($header, $this->header_row)];

                    if ( $header && $value ) {
                        $new_data[ $header ] = $value;
                    }

                }


                $data[] = $new_data;

            }



        $num_inserted = 0;

        $num_updated = 0;

        // Now for each row of data
        foreach ( $data as $model_set ){

            $model = new Model($model_name);

            if ( isset( $model_set['id'])){

                $model->find(new Key_Column($model_name)."='".$model_set['id']."'");

                if (

                    $model->exists

                    &&

                    $model->update($model_set)->put()
                )

                    $num_updated++;


            } else {

                if ( $model->create_from($model_set)->exists )

                    $num_inserted++;
            }

        }

        $this->num_inserted = $num_inserted;

        $this->num_updated = $num_updated;

        $this->data = $data;

    }

}