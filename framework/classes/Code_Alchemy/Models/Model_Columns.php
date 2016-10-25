<?php


namespace Code_Alchemy\Models;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\helpers\model_class_for;
use Code_Alchemy\models\model_wrapper;

class Model_Columns extends Array_Representable_Object {

    /**
     * @param string $canonical_name for Model
     * @throws \Exception
     */
    public function __construct( $canonical_name ){

        // Get the Model Class
        $model_class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For($canonical_name);

        if ( ! $model_class )

            throw new \Exception(get_called_class().": $canonical_name: Unknown Class or Model, make sure Model is deployed");

        $model = new model_wrapper( $model_class::model() );

        $deleted_clause = $model->model()->supports_soft_delete() ? "is_deleted='0'":'';

        $object = $model->model()->find_first($deleted_clause);

        $this->array_values = array_keys( $object->as_array() );

    }
}