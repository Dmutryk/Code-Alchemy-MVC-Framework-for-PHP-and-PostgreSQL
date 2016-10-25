<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/20/16
 * Time: 12:01 PM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Field_Label
 * @package Code_Alchemy\Models\Helpers
 *
 * Field Label
 */
class Field_Label extends Stringable_Object{

    /**
     * Field_Label constructor.
     * @param $field_name
     */
    public function __construct( $field_name ) {

        $this->string_representation = (string) new CamelCase_Name($field_name,'_',' ');

    }
}