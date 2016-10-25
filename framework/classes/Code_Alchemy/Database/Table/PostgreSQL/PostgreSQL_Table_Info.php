<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/17/15
 * Time: 12:24 PM
 */

namespace Code_Alchemy\Database\Table\PostgreSQL;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Database\Postgres\PostgreSQL_Fetch_All_Associative;
use Code_Alchemy\Database\Postgres\PostgreSQL_Table_Info_SQL;

/**
 * Class PostgreSQL_Table_Info
 * @package Code_Alchemy\Database\Table\PostgreSQL
 *
 * Table (columns) info for a PostgreSQL table or view
 */
class PostgreSQL_Table_Info extends Array_Representable_Object{

    /**
     * PostgreSQL_Table_Info constructor.
     * @param $table_or_view_name
     */
    public function __construct( $table_or_view_name ) {

        $this->array_values = (new PostgreSQL_Fetch_All_Associative(

            (string) new PostgreSQL_Table_Info_SQL($table_or_view_name)

        ));
    }
}