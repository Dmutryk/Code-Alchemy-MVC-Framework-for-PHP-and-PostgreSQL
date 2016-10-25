<?php


namespace Code_Alchemy\Templates\Layouts;


use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Templates\Template_File;
use Code_Alchemy\Text_Operators\String_Values_Replacer;

class Layout_Template extends Template_File{

    /**
     * @param string $full_path
     */
    public function __construct( $full_path ){

        // Load content
        $this->string = (string) new String_Values_Replacer(
            file_get_contents($full_path),
            array(
                '/__namespace__/'=>(string) new Namespace_Guess(),
                '/_mynamespace_/'=>(string) new Namespace_Guess()
            )
        );

    }
}