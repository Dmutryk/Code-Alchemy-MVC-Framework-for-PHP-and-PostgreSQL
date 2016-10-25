<?php


namespace __namespace__\Models\Triggers\After_Delete;


use Code_Alchemy\Models\Triggers\Custom_Trigger;

class __classname__ extends Custom_Trigger {

    /**
     * @param array $values
     */
    public function __construct( array $values ){

        // By default, copy all values over, but you cna change this
        $this->array_values = $values;

    }

}