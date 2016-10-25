<?php


namespace Code_Alchemy\Models\Triggers;


class After_Update extends Model_Trigger {

    /**
     * @param $model_name
     * @param array $values
     * @param array $previous_values
     * @param $update_result
     */
    public function __construct(
        $model_name,
        array $values,
        array $previous_values,
        $update_result
    ){

        // Pass assertions to user's custom trigger
        $this->parameters['previous_values'] = $previous_values;

        // Place values
        $this->array_values = $values;

        // Fire it!
        $this->fire_trigger('after_update',$model_name,$update_result,$values,$previous_values);


    }

}