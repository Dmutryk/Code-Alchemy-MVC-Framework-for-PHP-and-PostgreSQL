<?php


namespace Code_Alchemy\AngularJS\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

class Angular_Names_Replacements extends Array_Representable_Object {

    public function __construct( $name, $module = 'coreModule' ){

        $this->array_values = array(

            '/__module_name__/'=>$module,
            '/__directive_name__/'=>(string) new Directive_Name($name),
            '/__controller_name__/'=>(string) new Controller_Name($name),
            '/__template_name__/'=>$name
        );

    }
}