<?php


namespace Code_Alchemy\AngularJS\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class Controller_Name extends Stringable_Object{

    public function __construct( $html_name ){

        $pieces = explode('-', $html_name);

        for ( $i = 0; $i< count($pieces); $i++)

            $pieces[$i] = ucfirst( $pieces[$i]);

        $this->string_representation = implode('', $pieces);
    }
}