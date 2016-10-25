<?php


namespace Code_Alchemy\Specialists;


use Code_Alchemy\Core\Managed_Component;

abstract class Specialized_Component extends Managed_Component {

    /**
     * @var array of resulting data
     */
    protected $result_data = array();

    abstract public function perform_duties( $verbose = false );

    /**
     * @return array of resulting data
     */
    public function result(){

        return $this->result_data;

    }

}