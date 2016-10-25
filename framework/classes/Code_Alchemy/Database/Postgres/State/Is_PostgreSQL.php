<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/27/16
 * Time: 12:53 PM
 */

namespace Code_Alchemy\Database\Postgres\State;


use Code_Alchemy\Core\Boolean_Value;
use Code_Alchemy\Database\Database_Configuration_File;

/**
 * Class Is_PostgreSQL
 * @package Code_Alchemy\Database\Postgres\State
 *
 * Is Application using PostgreSQL Model?
 */
class Is_PostgreSQL extends Boolean_Value{

    public function __construct() {

        $this->boolean_value = !! (new Database_Configuration_File())->is_postgres();

    }
}