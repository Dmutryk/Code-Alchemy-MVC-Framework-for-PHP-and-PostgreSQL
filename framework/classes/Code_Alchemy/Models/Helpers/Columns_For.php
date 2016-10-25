<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Dynamic_Model;

class Columns_For extends Array_Representable_Object {

    /**
     * @param string $model_name to fetch columns
     */
    public function __construct( $model_name ){

        $class = (string) new Model_Class_For($model_name);

        $this->array_values = (new Model_Class_Verifier($class))->is_dynamic_model()?

            (new Dynamic_Model($model_name))->columns():

            (new $class)->source()->columns();

    }

}