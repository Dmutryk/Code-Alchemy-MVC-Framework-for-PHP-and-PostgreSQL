<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/23/15
 * Time: 3:15 PM
 */

namespace Code_Alchemy\Models\Components;


use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Models\Model_Configuration;

/**
 * Class Model_Settings
 * @package Code_Alchemy\Models\Components
 *
 * Model settings, for a given Model
 */
class Model_Settings extends Array_Object {

    /**
     * Model_Settings constructor.
     * @param array $model_name
     */
    public function __construct( $model_name ) {

        parent::__construct((new Model_Configuration())->model_for($model_name));

    }

    /**
     * @return array of after insert trigger settings for model
     */
    public function after_insert_triggers_settings(){

        $settings = array();

        if ( @$this->triggers['after_insert'] )

            $settings = $this->triggers['after_insert'];

        return $settings;

    }

}