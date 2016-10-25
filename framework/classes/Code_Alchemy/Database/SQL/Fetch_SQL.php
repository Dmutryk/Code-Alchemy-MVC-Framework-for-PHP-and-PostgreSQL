<?php


namespace Code_Alchemy\Database\SQL;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Fetch_SQL
 * @package Code_Alchemy\Database\SQL
 *
 * Construct Fetch SQL
 */
class Fetch_SQL extends Composed_SQL {

    /**
     * Construct Fetch SQL
     * @param $table
     * @param $key_column
     * @param $key_value
     */
    public function __construct( $table, $key_column, $key_value ){

        $sql = "SELECT * FROM `$table` WHERE `$key_column`='$key_value'";

        $this->string_representation = $sql;

    }

}