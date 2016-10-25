<?php


namespace __namespace__\Models\Triggers\After_Insert;


use Code_Alchemy\Models\Triggers\Custom_Trigger;

class __classname__ extends Custom_Trigger {

    /**
     * @param array $values
     * @param array $additional_parameters
     */
    public function __construct( array $values, array $additional_parameters ){

        // By default, copy all values over, but you cna change this
        $this->array_values = $values;

    }

}