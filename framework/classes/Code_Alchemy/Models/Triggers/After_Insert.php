<?php


namespace Code_Alchemy\Models\Triggers;


class After_Insert extends Model_Trigger {

    /**
     * @param $model_name
     * @param array $values
     * @param array $assertions
     * @param bool $is_changed to bubble changes back up the chain
     */
    public function __construct(
        $model_name,
        array $values,
        array $assertions,
        &$is_changed
    ){

        // Pass assertions to user's custom trigger
        $this->parameters['assertions'] = $assertions;

        // Place values
        $this->array_values = $values;

        // Fire it!
        $this->fire_trigger('after_insert',$model_name,$is_changed );


    }

}