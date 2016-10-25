<?php


namespace Code_Alchemy\Models\Triggers;


class After_Delete extends Model_Trigger {

    /**
     * @param $model_name
     * @param array $values
     */
    public function __construct(
        $model_name,
        array $values
    ){

        // Place values
        $this->array_values = $values;

        // Fire it!
        $this->fire_trigger('after_delete',$model_name);


    }

}