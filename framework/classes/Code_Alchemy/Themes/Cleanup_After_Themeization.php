<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 3:12 PM
 */

namespace Code_Alchemy\Themes;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Themes\Helpers\Theme_Original_Layouts_Directory;

class Cleanup_After_Themeization {

    public function __construct(){

        $filename = (string)new Theme_Original_Layouts_Directory();

        if ( ! file_exists($filename))

            // create as necessary
            mkdir( $filename );

        // Move all layouts
        shell_exec("mv ". Code_Alchemy_Framework::instance()->webroot()."/themes/".
        new Theme_Name_Guess()."/*.html $filename"
        );

        // Add to Git
        shell_exec("git add $filename/*.html");

    }

}