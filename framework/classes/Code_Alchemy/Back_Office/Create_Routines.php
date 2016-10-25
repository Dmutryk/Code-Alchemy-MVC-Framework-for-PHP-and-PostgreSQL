<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 11:03 PM
 */

namespace Code_Alchemy\Back_Office;


use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\SQL\Routine_SQL;

/**
 * Class Create_Routines
 * @package Code_Alchemy\Back_Office
 *
 * Creates necessary routines for Back Office to run
 */
class Create_Routines extends Back_Office{

    public function __construct() {

        foreach ( (new Missing_Database_Routines())->as_array() as $missing_routine ){

            // Get routine
            $routine = (string) new Routine_SQL($missing_routine);

            \FB::info($missing_routine);

            (new Database())->query($routine);
        }

    }


}