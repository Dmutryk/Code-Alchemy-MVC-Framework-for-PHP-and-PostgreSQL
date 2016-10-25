<?php


namespace Code_Alchemy\Core;


use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\JSON\JSON_File;

class Application_Configuration_File extends JSON_File {

    /**
     * Create a new instance
     */
    public function __construct(){

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();

        parent::__construct( array(

            // Set path
            'file_path' => $webapp_location.'/app/config/application.json',

            // Set template
            'template_file' => '/templates/JSON/application.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            // Automatically load file
            'auto_load'=>true,

    ));



    }

    /**
     * @return bool true if this is development
     */
    public function is_development(){

        $is_Development = !!($this->find('run-mode') == 'development');

        return $is_Development;

    }


}