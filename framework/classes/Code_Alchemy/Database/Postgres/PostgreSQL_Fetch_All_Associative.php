<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 10:59 PM
 */

namespace Code_Alchemy\Database\Postgres;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Database\Database;

/**
 * Class PostgreSQL_Fetch_All_Associative
 * @package Code_Alchemy\Database\Postgres
 *
 * Fetch all rows as associative arrays, given a PostgreSQL Query
 */
class PostgreSQL_Fetch_All_Associative extends Array_Representable_Object{

    /**
     * PostgreSQL_Fetch_All_Associative constructor.
     * @param $query
     */
    public function __construct( $query ){

        $result = (new Database())

            ->query($query,$is_error,true);

        $this->array_values = $result->fetchAll(\PDO::FETCH_ASSOC);

    }
}