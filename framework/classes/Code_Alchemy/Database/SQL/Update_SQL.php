<?php


namespace Code_Alchemy\Database\SQL;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Postgres\Is_Postgres_Model;
use Code_Alchemy\Database\SQL\Helpers\Empty_Field_Value_Corrector;
use Code_Alchemy\Database\SQL\Helpers\Is_Boolean_Column;
use Code_Alchemy\Database\SQL\Helpers\Is_Boolean_or_Int_Column;
use Code_Alchemy\Database\SQL\Helpers\Skip_Empty_Bool_Int_Postgres;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\Helpers\Current_User_Id;

class Update_SQL extends Composed_SQL{


    /**
     * @param $table_name
     * @param $columns
     * @param $values
     * @param $key_column
     * @param $key_value
     * @param bool|false $echo_back_sql to screen, for debugging
     */
    public function __construct(
        $table_name,
        $columns,
        $values,
        $key_column,
        $key_value,
        $echo_back_sql = false

    ){

        $values_set = '';

       foreach( $columns as $index=>$column){

            if($column == 'last_modified_date' OR $column == 'last_modified_by')
                continue;

            // Get field value
            $field_value = (string) new Empty_Field_Value_Corrector($values[$column],$column);


           if ( (new Skip_Empty_Bool_Int_Postgres($table_name,$field_value,$column))->bool_value()

           )
               continue;

               // If empty for Boolean
           if (

               (new Is_Boolean_Column($column))->bool_value()

               && (new Is_Postgres_Model($table_name))->bool_value()

           ){

               $field_value = $field_value ? 'TRUE':'FALSE';
           }


            // If foreign id and no value
            if ( (new Database_Table($table_name))->has_foreign_key( $column ) && ! $field_value ){

                // Just nullify it
                $field_value = 'NULL';
            }



            $real_escape_string =  is_string($field_value) ?

                (new Database())->real_escape_string($field_value, $table_name):

                $field_value;

            // For null
            if ( $field_value === 'NULL'){


                $values_set .= $values_set? " , $column = NULL ": " $column = NULL ";

            }


            else

            $values_set .= $values_set? " , `$column`  = '".

                    $real_escape_string.

                    "' ":" $column = '".

                $real_escape_string

                    ."' ";

        }

        // Add last modified data and last modified by
        $current_user_id =

            isset($values['last_modified_by']) && $values['last_modified_by']

            ? $values['last_modified_by']:

            (string)(new Current_User_Id());

        $values_set .= ", last_modified_date='".date('Y-m-d H:i:s'). "' ".

            ($current_user_id?  " ,last_modified_by=". $current_user_id ." ":' ');


        $sql = "UPDATE `$table_name` SET $values_set WHERE `$key_column` = $key_value";

        if ( $echo_back_sql ) echo get_called_class().": SQL = $sql\r\n";

       // if ( $this->debug || $this->is_development() ) \FB::info(get_called_class().": SQL = $sql");

        $this->string_representation = $sql;

    }

}
