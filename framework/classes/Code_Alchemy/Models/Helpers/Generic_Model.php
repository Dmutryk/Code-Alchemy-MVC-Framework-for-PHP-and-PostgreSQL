<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Models\Model;

class Generic_Model {

    /**
     * @var \Code_Alchemy\Models\Model
     */
    private $model;

    /**
     * @param Model $model to represent
     */
    public function __construct( $model ){

        $this->model = $model;

    }

    /**
     * @return Model
     */
    public function model(){

        return $this->model;

    }

}