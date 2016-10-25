<?php


namespace Code_Alchemy\Controllers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\apis\directory_api;

class Application_Controllers extends Array_Representable_Object {

    public function __construct(){

        $dir = new directory_api( getcwd()."/app/controllers" );

        $this->array_values = $dir->directory_listing(true);

    }

    /**
     * @return Application_Controllers
     */
    public static function create(){

        return new self;

    }
}