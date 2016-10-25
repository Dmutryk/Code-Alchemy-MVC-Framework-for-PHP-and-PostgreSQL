<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/22/15
 * Time: 3:43 PM
 */

namespace Code_Alchemy\Database\Postgres;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Empty_PostgreSQL_Query
 * @package Code_Alchemy\Database\Postgres
 *
 * True, if given query is empty
 */
class Is_Empty_PostgreSQL_Query extends Boolean_Value{

    /**
     * Is_Empty_PostgreSQL_Query constructor.
     * @param $query
     */
    public function __construct( $query ) {

        //\FB::info(get_called_class()." Query is $query");

        $this->boolean_value = !! preg_match("/FROM\s+\"\"\s+WHERE/i",$query);
    }
}