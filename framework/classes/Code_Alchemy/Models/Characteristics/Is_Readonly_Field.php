<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/28/16
 * Time: 5:52 PM
 */

namespace Code_Alchemy\Models\Characteristics;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Readonly_Field
 * @package Code_Alchemy\Models\Characteristics
 *
 * Is the given field readonly, in the context of the given model name?
 */
class Is_Readonly_Field extends Boolean_Value {

    /**
     * Is_Readonly_Field constructor.
     * @param string $field_name
     * @param string $model_name
     */
    public function __construct( $field_name, $model_name ) {

        $this->boolean_value = in_array($field_name,[

            $model_name.'_id', 'uuid'

        ]);
    }
}