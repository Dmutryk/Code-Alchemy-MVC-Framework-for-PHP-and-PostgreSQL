<?php


namespace Code_Alchemy\Templates\HTML5;


use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Templates\Template_File;
use Code_Alchemy\Themes\Helpers\Theme_Root;

class Layout_Post_Stub extends Template_File {

    /**
     * Load template
     */
    public function __construct(){

        $this->string =

            preg_replace(
            '/__name__/',
            (string) new Namespace_Guess() ,
            file_get_contents(
                (string) new Code_Alchemy_Root_Path()."/templates/html5_theme/layout_post_stub.php")
        );

    }

}