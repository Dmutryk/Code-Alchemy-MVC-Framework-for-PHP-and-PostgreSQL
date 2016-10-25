<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Dynamic_Model;

class Intersections_For extends Array_Representable_Object {

    public function __construct( $model_name ){

        $class = (string) new Model_Class_For($model_name);

        // now get intersections
        $this->array_values = (new Model_Class_Verifier($class))->is_dynamic_model()?

            (new Dynamic_Model($model_name))->intersections():

            (new $class)->source()->intersections();


    }

}