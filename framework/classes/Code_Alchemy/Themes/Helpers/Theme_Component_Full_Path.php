<?php


namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Theme_Name_Guess;

class Theme_Component_Full_Path extends Stringable_Object {

    /**
     * @param string $component_name
     */
    public function __construct( $component_name ){

        $this->string_representation = (string) new Theme_Component_Directory((string) new Theme_Name_Guess()).
            "$component_name.php";

    }
}