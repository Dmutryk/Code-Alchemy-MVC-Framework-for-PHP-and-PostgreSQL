<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/27/16
 * Time: 5:35 PM
 */

namespace Code_Alchemy\Database\SQL;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class SQL_Field_Declaration
 * @package Code_Alchemy\Database\SQL
 */
class SQL_Field_Declaration extends Stringable_Object{

    /**
     * SQL_Field_Declaration constructor.
     * @param $field_name
     * @param $field_type
     * @param bool $is_null
     * @param null $default_value
     */
    public function __construct( $field_name, $field_type, $is_null = true, $default_value = null ) {

        $this->string_representation =

        $field_name ." ". strtoupper($field_type) .

            ' '. ($is_null ? ' ' :' NOT NULL')

            .($default_value ? " DEFAULT ".$this->default_value($default_value,$field_type)." ":' ').", ";


    }

    /**
     * @param $default_value
     * @param $field_type
     * @return string
     */
    private function default_value( $default_value, $field_type ){


        if ( strtoupper($field_type) == 'BOOLEAN')

            $default_value = (bool) $default_value ? 'TRUE':'FALSE';

        else $default_value = "'$default_value'";

        return $default_value;
    }
}