<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/22/15
 * Time: 8:58 PM
 */

namespace Code_Alchemy\Models;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Models\Components\Model_Settings;

/**
 * Class Key_Column
 * @package Code_Alchemy\Models
 *
 * Key column for given Model (by name)
 */
class Key_Column extends Stringable_Object{

    /**
     * @param string $model_name
     */
    public function __construct( $model_name ){

        $model_Settings = (new Model_Settings($model_name))->as_array();

        $this->string_representation = isset($model_Settings['key_column'])

            ? $model_Settings['key_column']: 'id';

        //\FB::info(get_called_class().": Key column for $model_name is $this->string_representation");



    }
}