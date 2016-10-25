<?php


namespace Code_Alchemy\Creators;


use Code_Alchemy\Core\Managed_Component;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;

abstract class Entity_Creator extends Managed_Component {

    /**
     * @var bool true to simulate
     */
    protected $simulate = false;


    abstract public function create( $verbose = false );


}