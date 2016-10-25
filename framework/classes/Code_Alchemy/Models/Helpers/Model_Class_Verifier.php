<?php


namespace Code_Alchemy\Models\Helpers;


class Model_Class_Verifier {

    /**
     * @var string Model Class Name
     */
    private $model_class_name = '';

    /**
     * @param string $model_class_name
     */
    public function __construct( $model_class_name ){

        $this->model_class_name = $model_class_name;

    }

    /**
     * @return bool true if this is a Dynamic Model
     */
    public function is_dynamic_model(){

        return !! preg_match('/dynamic_model/i',$this->model_class_name);

    }

}