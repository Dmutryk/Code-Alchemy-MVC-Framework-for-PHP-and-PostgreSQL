<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 10:58 PM
 */

namespace Code_Alchemy\Database\Postgres;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class PostgreSQL_Routines
 * @package Code_Alchemy\Database\Postgres
 *
 * An array of PostgreSQL routines in the current Database
 */
class PostgreSQL_Routines extends Array_Representable_Object{

    /**
     * PostgreSQL_Routines constructor.
     * @param string $schema_name
     */
    public function __construct( $schema_name = 'public' ){

        $routines = array();

        $associative_results = (new PostgreSQL_Fetch_All_Associative(

            (string)new PostgreSQL_Routine_Names_SQL($schema_name)

        ))->as_array();

        foreach( $associative_results as $result )

            $routines[] = $result['proname'];

        $this->array_values = $routines;

        //\FB::info($this->array_values);

    }
}