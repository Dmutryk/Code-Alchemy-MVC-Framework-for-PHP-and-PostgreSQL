<?php


namespace Code_Alchemy\Database\SQL;


class Delete_SQL extends Composed_SQL {

    /**
     * @param $table_name
     * @param $key_column
     * @param $key_value
     */
    public function __construct( $table_name, $key_column, $key_value ){

        $this->string_representation =

            "DELETE FROM `$table_name` WHERE `$key_column`='$key_value'";

       // if ( $this->is_development() ) \FB::info(get_called_class().": SQL is $this->string_representation");

    }

}