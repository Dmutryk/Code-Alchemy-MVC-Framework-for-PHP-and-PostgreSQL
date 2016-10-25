<?php


namespace __namespace__\Models\Triggers\Before_Update;


use Code_Alchemy\Models\Triggers\Custom_Trigger;

class __classname__ extends Custom_Trigger {

    /**
     * @param array $update_values to be applied as part of update
     * @param array $current_members from the Model
     */
    public function __construct( array $update_values, array $current_members ){

        // By default, copy all values over, but you cna change this
        $this->array_values = $update_values;

    }

}