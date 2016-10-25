<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/10/16
 * Time: 3:29 PM
 */

namespace Code_Alchemy\Models\Attributes;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Models\Components\Model_Settings;

/**
 * Class Editable_Model_Name
 * @package Code_Alchemy\Models\Attributes
 *
 * Gets the Editable Model name for a given model
 */
class Editable_Model_Name extends  Stringable_Object{

    /**
     * Editable_Model_Name constructor.
     * @param string $model_name
     */
    public function __construct( $model_name ) {

        $candidate = @(new Model_Settings($model_name))->as_array()['edit_model'];

        $this->string_representation = $candidate ? $candidate: $model_name;
    }
}