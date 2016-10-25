<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/21/16
 * Time: 2:33 PM
 */

namespace Code_Alchemy\Models\Triggers\Automated;


use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Models\Components\Model_Settings;

class After_Insert_Triggers_Configuration extends Array_Object{

    /**
     * After_Triggers_Configuration constructor.
     * @param array $model_name
     */
    public function __construct($model_name) {

        parent::__construct(

            (new Model_Settings($model_name))->after_insert_triggers_settings()

        );

    }

}