<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 5:41 PM
 */

namespace Code_Alchemy\Database\SQL\Helpers;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Date_Column
 * @package Code_Alchemy\Database\SQL\Helpers
 *
 * Is the given column a date field?
 */
class Is_Date_Column extends Boolean_Value{

    /**
     * @param string $column_name
     */
    public function __construct( $column_name ){

        $this->boolean_value = !! preg_match('/^([a-z0-9_\-]+)_date$/i',$column_name);

    }
}