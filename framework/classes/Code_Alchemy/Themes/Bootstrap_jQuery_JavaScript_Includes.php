<?php


namespace Code_Alchemy\Themes;


use Code_Alchemy\Core\Array_Representable_Object;

class Bootstrap_jQuery_JavaScript_Includes extends Array_Representable_Object {

    public function __construct(){

        $this->array_values = array(

            // to match jquery library
            'js\/jquery\-',

            // to match jquery and libs
            'jquery\.',

            // to match handlebars
            'handlebars',

            // Column resizeable
            'colResizable',

            // Bootstrap JS
            'js\/bootstrap'


        );
    }
}