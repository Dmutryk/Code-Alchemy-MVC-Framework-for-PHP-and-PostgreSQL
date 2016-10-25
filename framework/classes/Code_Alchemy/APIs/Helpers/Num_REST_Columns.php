<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/18/15
 * Time: 10:20 PM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Integer_Value;

/**
 * Class Num_REST_Columns
 * @package Code_Alchemy\APIs\Helpers
 *
 * Get the number of columns for a REST result
 */
class Num_REST_Columns extends Integer_Value{

    public function __construct(){

        $num_columns = 1;

        if ( isset( $_REQUEST['_columns']) && is_numeric($_REQUEST['_columns']) &&

        $_REQUEST['_columns'] > 1 )

            $num_columns = (int) $_REQUEST['_columns'];

        $this->integer_value = $num_columns;
    }

}