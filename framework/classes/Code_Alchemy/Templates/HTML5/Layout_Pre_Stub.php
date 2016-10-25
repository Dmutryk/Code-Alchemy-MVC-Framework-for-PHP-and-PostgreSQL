<?php


namespace Code_Alchemy\Templates\HTML5;


use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Templates\Template_File;
use Code_Alchemy\Themes\Helpers\Theme_Root;

class Layout_Pre_Stub extends Template_File {

    /**
     * Load template
     */
    public function __construct(){

        $preg_replace = preg_replace(
            '/__theme_root__/',
            (string)new Theme_Root('', (string)new Theme_Name_Guess()),
            file_get_contents(
                (string)new Code_Alchemy_Root_Path() . "/templates/html5_theme/layout_pre_stub.php")
        );

        $preg_replace = preg_replace('/__mynamespace__/',(string)new Namespace_Guess(),$preg_replace);

        $this->string =

            $preg_replace;

    }

}