<?php


namespace Code_Alchemy\Models\Triggers;

use Code_Alchemy\Applications\Helpers\Application_Root;
use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Helpers\Namespace_Guess;

/**
 * Class Before_Update
 * @package Code_Alchemy\Models\Triggers
 *
 * Trigger fires before every Model Update is applied
 */
class Before_Update extends Model_Trigger {

    /**
     * @param $model_name
     * @param array $values
     * @param array $members
     */
    public function __construct( $model_name, array $values, array $members ){

        // for users
        if ( $model_name == 'user')

            // Fire User trigger
            $values = (new User_Before_Update($values,$members))->as_array();

        $this->array_values = $values;

        // Fire the trigger
        $this->fire_trigger('before_update',$model_name,$is_changed,$members);

    }

}