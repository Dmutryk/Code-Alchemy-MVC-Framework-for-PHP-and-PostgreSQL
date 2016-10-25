<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 5:41 PM
 */

namespace Code_Alchemy\Database\SQL\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Empty_Field_Value_Corrector
 * @package Code_Alchemy\Database\SQL\Helpers
 *
 * Corrects empty field values
 */
class Empty_Field_Value_Corrector extends Stringable_Object{

    /**
     * @param string $field_value
     * @param string $field
     * @throws \Exception
     */
    public function __construct( $field_value, $field ){



        $this->string_representation =

            (
                // Is it a column to set null?
                in_array($field,(new Set_NULL_Columns_for_PostgreSQL())->as_array())

                ||

                // Or is it a date column?
                (new Is_Date_Column($field))->bool_value()
            )
             && ! $field_value ?

                'NULL' : (string) $field_value;



    }
}