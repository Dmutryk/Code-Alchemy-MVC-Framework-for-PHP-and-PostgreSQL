<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 7:52 PM
 */

namespace Code_Alchemy\Database\Table\PostgreSQL;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Database\Database;

/**
 * Class PostgreSQL_Database_Tables
 * @package Code_Alchemy\Database\Table
 *
 * Gets a list of all PostgreSQL database tables
 */
class PostgreSQL_Database_Tables extends Array_Representable_Object{

    public function __construct( $schema = 'public' ){

        $tables = array();

        foreach( (new Database())->query("SELECT * FROM information_schema.tables WHERE table_schema = '$schema'")

                     ->fetchAll(\PDO::FETCH_ASSOC) as $schema_table_info )

            $tables[] = (new PostgreSQL_Table($schema_table_info))->as_array();

        $this->array_values = $tables;
    }
}