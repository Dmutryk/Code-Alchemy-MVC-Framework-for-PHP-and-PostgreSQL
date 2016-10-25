<?php


namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class Theme_Component_Directory extends Stringable_Object {

    /**
     * @param string $theme_name
     */
    public function __construct( $theme_name ){

        $this->string_representation = getcwd()."/app/views/components/$theme_name/";

    }
}