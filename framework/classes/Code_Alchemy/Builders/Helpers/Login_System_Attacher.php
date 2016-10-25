<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/13/15
 * Time: 9:36 PM
 */

namespace Code_Alchemy\Builders\Helpers;


use Code_Alchemy\Filesystem\Text_File_As_Lines;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Themes\Helpers\Theme_Directory;

class Login_System_Attacher {


    /**
     * Attach login system
     */
    public function attach(){

        // Get theme directory
        $dir = (string) new Theme_Directory();

        // Layouts we'll be working with
        $layouts = array( "$dir/login.php","$dir/register.php","$dir/lost_password.php");

        // For each one
        foreach ( $layouts as $layout )

            if ( file_exists($layout) ){

                // Write back to same file
                file_put_contents(

                    $layout,

                    // Get Lines
                    (new Text_File_As_Lines($layout))

                        // Insert right after form...
                        ->insert_lines_after("form class=\"login-form\"",

                            // Insert an alert
                            (new Text_File_As_Lines(

                                (string) new Code_Alchemy_Root_Path()."/templates/fragments/login-form-alert.php"

                            ))->as_array()

                            )->as_string()

                );
            }

    }

}