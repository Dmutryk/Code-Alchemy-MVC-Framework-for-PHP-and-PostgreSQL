<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 11:05 PM
 */

namespace Code_Alchemy\Back_Office;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Database\Postgres\PostgreSQL_Routines;

/**
 * Class Missing_Database_Routines
 * @package Code_Alchemy\Back_Office
 *
 * Gets a list of routines missing from the database
 */
class Missing_Database_Routines extends Array_Representable_Object{

    public function __construct(){

        $this->array_values = array_diff( array('table_fields_info'),(new PostgreSQL_Routines())->as_array());

    }
}