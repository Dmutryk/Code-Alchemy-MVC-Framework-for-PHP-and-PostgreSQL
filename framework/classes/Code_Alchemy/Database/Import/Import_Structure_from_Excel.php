<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/4/15
 * Time: 5:10 PM
 */

namespace Code_Alchemy\Database\Import;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\JSON\JSON_File;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Vendors\Sergey_Shuchkin\SimpleXLSX;

/**
 * Class Import_Structure_from_Excel
 * @package Code_Alchemy\Database\Import
 *
 * Imports database structure from one or more sheets in an Excel file
 */
class Import_Structure_from_Excel extends Array_Representable_Object{

    /**
     * @param string $specification_json_filename
     */
    public function __construct(

        $specification_json_filename,

        callable $insert_callback

    ){

        // track models inserted
        $num_models_inserted = array();

        // Track errors
        $errors = array();

        $config = new JSON_File(array(

            'file_path' => $specification_json_filename,

            'auto_load' => true

        ));

        $excel_file = new SimpleXLSX($config->find('import-filename'));

        foreach ( $excel_file->sheets() as $id => $sheet ){

            $model_name = $excel_file->sheetName($id);

            $num_models_inserted[$model_name] = 0;

            $header = new Import_Structure_Header();

            foreach ( $excel_file->rows($id) as $row ){

                if ( ! $header->is_set() )

                    $header->set($row);

                else {

                    $map_values = $header->map_values($row);

                    $model = (new Model($model_name))

                        ->create_from($map_values);

                    if ( $model->exists )

                        $num_models_inserted[$model_name]++;

                    else $errors[] = $model->error();

                    $insert_callback( $model_name, $row, $map_values, $model);

                }

            }

        }

        $this->num_models_inserted = $num_models_inserted;

        $this->errors = $errors;

    }

}