<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/8/15
 * Time: 11:28 PM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Boolean_Value;
use Code_Alchemy\Models\Model_Configuration;

/**
 * Class Supports_Safe_Delete
 * @package Code_Alchemy\Models\Helpers
 *
 * True, if given model supports safe delete
 */
class Supports_Safe_Delete extends Boolean_Value{

    /**
     * Supports_Safe_Delete constructor.
     * @param string $model_name
     */
    public function __construct( $model_name ){

        $config = (new Model_Configuration())

            ->model_for($model_name);

        $this->boolean_value =

            !! ( isset( $config['safe_delete']) && $config['safe_delete']);
    }
}