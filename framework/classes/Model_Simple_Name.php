<?php


namespace Code_Alchemy\helpers;


class Model_Simple_Name {

    /**
     * @var string Simple Name
     */
    private $simple_name = '';

    /**
     * @param string $model_name including namespaces
     */
    public function __construct( $model_name ){

        $this->simple_name = array_pop( explode('\\',$model_name) );

    }

    /**
     * @return string simple name
     */
    public function __toString(){ return $this->simple_name; }
}