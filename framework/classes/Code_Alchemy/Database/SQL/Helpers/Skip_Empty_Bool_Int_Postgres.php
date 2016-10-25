<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 5:28 PM
 */

namespace Code_Alchemy\Database\SQL\Helpers;


use Code_Alchemy\Core\Boolean_Value;
use Code_Alchemy\Database\Postgres\Is_Postgres_Model;

/**
 * Class Skip_Empty_Bool_Int_Postgres
 * @package Code_Alchemy\Database\SQL\Helpers
 *
 * True if we should skip this value, since its empty
 * but belongs to an int or boolean value
 */
class Skip_Empty_Bool_Int_Postgres extends Boolean_Value {

    /**
     * @param string $table_name to check
     * @param string $field_value to check
     * @param string $column to check
     * @throws \Exception
     */
    public function __construct( $table_name, $field_value, $column ){

        $this->boolean_value =

            // It's a postgres model
            (new Is_Postgres_Model($table_name))->bool_value() &&

                // The value is empty
                ! $field_value &&

                // It's an Id or boolean column
            (new Is_Int_Column($column))->bool_value();


    }

}