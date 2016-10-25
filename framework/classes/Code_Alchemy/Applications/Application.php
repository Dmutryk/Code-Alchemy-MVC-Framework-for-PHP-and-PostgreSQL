<?php


namespace Code_Alchemy\Applications;


use Code_Alchemy\Applications\Helpers\Application_Root;
use Code_Alchemy\Applications\Toolboxes\Helpers\Text_Colorizer;
use Code_Alchemy\Core\Managed_Component;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Helpers\Called_Class;
use Code_Alchemy\Templates\Controllers\Controller_Template;
use Code_Alchemy\Templates\Layouts\Component_Template;
use Code_Alchemy\Templates\Layouts\Layout_Template;
use Code_Alchemy\apis\directory_api;

class Application extends Managed_Component {

    /**
     * @var string Version number
     */
    protected $version = '1.0.0';

    /**
     * @return string Version Number
     */
    public function version(){

        return $this->version;

    }

    /**
     * @param bool $verbose to send output
     */

    public function deploy( $verbose = false ){

        $name = (string)new Text_Colorizer($this->name(),'SUCCESS');

        if ( $verbose ) echo "\r\n\tDeploying $name version $this->version\r\n";

        // Put components
        foreach ( $this->images() as $filename ){

            $destination_file = getcwd() . "/img/$filename";

            if ( copy( $this->root()."/img/$filename",
                $destination_file))

                shell_exec("git add $destination_file");
        }


        // Put components
        foreach ( $this->components() as $filename ){

            $destination_file = getcwd() . "/app/views/components/$filename";

            if ( file_put_contents( $destination_file,
                (string)new Component_Template(
                    $this->root()."/components/$filename"
                )))

                shell_exec("git add $destination_file");

        }


        // For each controller
        foreach ( $this->controllers() as $filename ){

            $destination_file = getcwd() . "/app/controllers/$filename";

            if ( file_put_contents( $destination_file,
                (string)new Controller_Template(
                    $this->root()."/controllers/$filename"
                )))

                shell_exec("git add $destination_file");

        }

        // Same for each Layout
        foreach ( $this->layouts() as $filename ){

            $destination_file = getcwd() . "/app/views/layouts/$filename";

            if ( file_put_contents( $destination_file,
                (string)new Layout_Template(
                    $this->root()."/layouts/$filename"
                )))

                shell_exec("git add $destination_file");

        }

        // Copy over templates
        foreach ( $this->templates() as $filename ){

            $destination_file = getcwd() . "/templates/$filename";

            if ( file_put_contents( $destination_file,

                file_get_contents($this->root()."/templates/$filename")

            ))

                shell_exec("git add $destination_file");

        }


        // Copy over various components
        foreach ( array('css','js','templates') as $file )

            // Copy over Stylesheets
            foreach ( $this->files( $file ) as $filename ){

                $destination = getcwd() . "/$file/$filename";

                $copier = new Smart_File_Copier(
                    $this->root()."/$file/$filename",
                    $destination,
                    array(),
                    isset($this->user_options['overwrite'])
                );

                if ( $copier->copy( $verbose ) ) shell_exec("git add $destination");
            }

    }

    /**
     * @param string $subdir to search
     * @return array of files
     */
    private function files( $subdir ){

        $dir = new directory_api( $this->root()."/$subdir");

        return $dir->directory_listing();

    }

    /**
     * @return array of Templates
     */
    private function templates(){

        $dir = new directory_api( $this->root()."/templates");

        return $dir->directory_listing();

    }


    /**
     * @return array of Stylesheets
     */
    private function stylesheets(){

        $dir = new directory_api( $this->root()."/css");

        return $dir->directory_listing();

    }

    /**
     * @return array of Stylesheets
     */
    private function images(){

        $dir = new directory_api( $this->root()."/img");

        return $dir->directory_listing();

    }

    /**
     * @return array of Controllers
     */
    private function controllers(){

        $dir = new directory_api( $this->root()."/controllers");

        return $dir->directory_listing();

    }

    /**
     * @return array of Controllers
     */
    private function components(){

        $dir = new directory_api( $this->root()."/components");

        return $dir->directory_listing();

    }


    /**
     * @return array of Layouts
     */
    private function layouts(){

        $dir = new directory_api( $this->root()."/layouts");

        return $dir->directory_listing();

    }

    /**
     * @return string name of application
     */
    public function name(){

        return (string)new Called_Class($this);

    }

    protected function root(){

        return (string) new Application_Root( $this->name() );

    }

}