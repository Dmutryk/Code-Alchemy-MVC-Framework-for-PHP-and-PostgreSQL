<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/17/15
 * Time: 12:22 PM
 */

namespace Code_Alchemy\Database\Postgres;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class PostgreSQL_Table_Info_SQL
 * @package Code_Alchemy\Database\Postgres
 *
 * SQL to fetch PostgreSQL Table info
 */
class PostgreSQL_Table_Info_SQL extends Stringable_Object{

    /**
     * PostgreSQL_Table_Info_SQL constructor.
     * @param string $table_or_view_name to fetch info
     */
    public function __construct( $table_or_view_name ){

        $SQL = "select column_name,ordinal_position,column_default,is_nullable,data_type,is_identity from information_schema.columns where table_name = '$table_or_view_name'";

        $this->string_representation = $SQL;
    }
}