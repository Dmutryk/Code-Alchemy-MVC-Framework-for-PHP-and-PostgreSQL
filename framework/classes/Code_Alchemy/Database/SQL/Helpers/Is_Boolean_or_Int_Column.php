<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 5:31 PM
 */

namespace Code_Alchemy\Database\SQL\Helpers;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Boolean_or_Int_Column
 * @package Code_Alchemy\Database\SQL\Helpers
 *
 * True if given column is boolean or int
 */
class Is_Boolean_or_Int_Column extends Boolean_Value{

    /**
     * @param string $column
     */
    public function __construct( $column ){

        $this->boolean_value =

            preg_match('/([a-zA-Z0-9_]+)_id$/',$column)

            ||

            preg_match('/^is_([a-zA-Z0-9_]+)$/',$column);

    }

}