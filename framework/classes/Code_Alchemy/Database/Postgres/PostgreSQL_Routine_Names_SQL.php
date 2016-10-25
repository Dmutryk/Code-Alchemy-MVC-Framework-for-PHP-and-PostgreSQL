<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 10:54 PM
 */

namespace Code_Alchemy\Database\Postgres;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class PostgreSQL_Routine_Names_SQL
 * @package Code_Alchemy\Database\Postgres
 *
 * SQL to select PostgreSQL Routine Names
 */
class PostgreSQL_Routine_Names_SQL extends Stringable_Object{

    /**
     * PostgreSQL_Routine_Names_SQL constructor.
     * @param string $schema_name
     */
    public function __construct( $schema_name = 'public') {

        $this->string_representation = "SELECT p.proname FROM pg_catalog.pg_namespace n JOIN pg_catalog.pg_proc p ".
            "ON p.pronamespace = n.oid WHERE n.nspname = '$schema_name'";

    }
}