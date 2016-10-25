<?php


namespace Code_Alchemy\Templates\Layouts;


use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Templates\Template_File;

class AngularJS_Layout extends Template_File {

    /**
     * Load the Layout
     */
    public function __construct(){

        $this->string = file_get_contents( (string) new Code_Alchemy_Root_Path()."/templates/views/layouts/angularjs.php");

    }

}