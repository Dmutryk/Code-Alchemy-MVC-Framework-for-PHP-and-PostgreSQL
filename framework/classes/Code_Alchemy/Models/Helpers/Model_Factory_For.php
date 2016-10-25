<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Models\Dynamic_Model;

class Model_Factory_For {

    /**
     * @var \Code_Alchemy\Models\Factories\Model_Factory
     */
    private $factory;

    /**
     * @var bool true for firebug output
     */
    private $firebug = false;

    /**
     * @param string $model_name
     */
    public function __construct( $model_name ){

        if ( $this->firebug) \FB::info(get_called_class().": constructed for $model_name");

        $model_class = (string) new Model_Class_For($model_name);

        $this->factory = (new Model_Class_Verifier($model_class))->is_dynamic_model()?

            (new Dynamic_Model($model_name))->get_factory():

                $model_class::factory();

    }

    /**
     * @return \Code_Alchemy\Models\Factories\Model_Factory
     */
    public function factory(){

        return $this->factory;
    }

}