<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/27/16
 * Time: 5:17 PM
 */

namespace Code_Alchemy\Creators\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\SQL\SQL_Field_Declaration;

/**
 * Class Custom_Fields_SQL_String
 * @package Code_Alchemy\Creators\Helpers
 *
 * Translates custom fields spec into SQL
 */
class Custom_Fields_SQL_String extends Stringable_Object{

    public function __construct( \stdClass $fields_spec, array $mls_languages = [] ) {

        $sql = '';

        foreach ( $fields_spec as $field_name => $settings ){

            if ( count( $mls_languages ) && @$settings->is_mls ){

                foreach( $mls_languages as $language )

                    $sql .=

                        (string) new SQL_Field_Declaration($field_name."_$language",$this->field_type($settings),@$settings->is_null,$settings->default)."\r\n";

            } else

            $sql .=

                (string) new SQL_Field_Declaration($field_name,$this->field_type($settings),@$settings->is_null,$settings->default)."\r\n";

        }

        $this->string_representation = $sql;

    }

    /**
     * @param \stdClass $settings
     * @return string
     */
    private function field_type( \stdClass $settings ){

        $type = $settings->type;

        switch( strtoupper($settings->type) ){

            case 'VARCHAR':

                        $type = $settings->length ? "$type($settings->length)" : $type;

                break;

        }

        return $type;

    }
}