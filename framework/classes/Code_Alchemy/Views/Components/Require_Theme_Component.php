<?php


namespace Code_Alchemy\Views\Components;


use Code_Alchemy\Core\Require_File_Once;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\parnassus;

class Require_Theme_Component extends Require_File_Once{

    /**
     * @param $component_name
     */
    public function __construct( $component_name ){

        parent::__construct( parnassus::instance()->webroot().'/app/views/components/'.

            (string) new Theme_Name_Guess()."/$component_name.php"
        );
    }
}