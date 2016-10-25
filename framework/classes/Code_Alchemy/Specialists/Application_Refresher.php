<?php


namespace Code_Alchemy\Specialists;


use Code_Alchemy\Directors\Theme_Director;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Helpers\Theme_Name_Guess;

class Application_Refresher extends Specialized_Component {

    /**
     * @param bool $verbose true to send output to screen
     */
    public function perform_duties( $verbose = false ){

        if ( $verbose) echo "Refreshing application....\r\n";

        // Get working dir
        $working_dir = getcwd();

        // Get root
        $root = $this->root();

        echo "We'll be working inside of $working_dir, from $root.\r\n";

        // Step 1: check for index.php
        $index_file = "$working_dir/index.php";

        if ( ! file_exists($index_file) ){

            $source_index = "$root/templates/index.newwebapp.php";

            echo "This application lacks an index.php file.\r\n";

            $copy = new Smart_File_Copier(
                $source_index,$index_file,array(
                '/__root__/'=>$root,
                '/__working_dir__/'=>$working_dir
            ),false);

            if ( $copy->copy() ){

                if ( $verbose ) echo "index.php: Added to application.\r\n";

            } else {

                echo "Error: ".$copy->error;

            }


        }

        //Last step, we want to call Theme Director
        $director = new Theme_Director((string) new Theme_Name_Guess(),$this->user_options);

        $director->insert_fragments();

        // Next step, copy over javascript
        $copy = new Smart_File_Copier(
            $root."/templates/js/codeAlchemy.js",$working_dir."/js/codeAlchemy.js",array(
        ),true);

        if ( $copy->copy() ){

            if ( $verbose ) echo "Code_Alchemy: Added/Updated Code Alchemy JS Module.\r\n";

        } else {

            echo "Error: ".$copy->error;

        }

        if ( $verbose ) echo "Done refreshing application.\r\n\r\n";


    }

}