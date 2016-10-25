<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/21/16
 * Time: 2:27 PM
 */

namespace Code_Alchemy\Models\Triggers\Automated;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Factories\Relate_InsFetched_Model;
use Code_Alchemy\Models\Factories\Relate_InsFetched_Models;

/**
 * Class Automated_After_Insert_Trigger
 * @package Code_Alchemy\Models\Triggers\Automated
 *
 * Automated After insert trigger, fires after insert on any model, and
 * takes action based on configured trigger actions in Models.json
 */
class Automated_After_Insert_Trigger extends Array_Representable_Object{

    /**
     * @var string Model Name against which trigger is fired
     */
    private $modelName = '';

    /**
     * Automated_After_Insert_Trigger constructor.
     * @param $modelName
     * @param array $modelMembers
     * @param array $insertAssertions
     */
    public function __construct( $modelName, array $modelMembers, array $insertAssertions ) {

        $this->modelName = $modelName;

        $config = new After_Insert_Triggers_Configuration($modelName);

        if ( $this->_firebug ) {

            \FB::info(get_called_class().": Firing triggers as needed for $modelName");

            \FB::info($config->as_array());
        }

        // If we have at least one
        foreach ( $config->as_array() as $trigger_type => $trigger ){

            if ( $this->_firebug ) \FB::info(get_called_class().":$trigger_type: Firing this trigger");

            $this->_fire_trigger( $trigger_type, $trigger, $insertAssertions, $modelMembers );

        }

        // By default pass through members
        $this->array_values = $modelMembers;

    }

    /**
     * @param $trigger_type
     * @param array $trigger
     * @param array $assertions
     * @param array $modelMembers
     */
    private function _fire_trigger( $trigger_type, array $trigger, array $assertions, array $modelMembers ){

        switch ( $trigger_type ){

            case 'create_related':

                foreach ( $trigger as $label => $concept ){

                    $relation = new Relate_InsFetched_Models(

                        $concept['insfetch'],

                        is_array(@$assertions[$label])?$assertions[$label]:array(),

                        $concept['relationship'],$modelMembers,$this->modelName);

                }

        }
    }
}