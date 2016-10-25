<?php


namespace Code_Alchemy\Themes;


use Code_Alchemy\JSON\JSON_File;

class Theme_Manager_Configuration extends JSON_File {

    /**
     * @param array $options
     */
    public function __construct( array $options = array() ){

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();

        parent::__construct( array_merge(

            $options,

            array(

                // Set path
                'file_path' => $webapp_location.'/app/config/theme-manager.json',

                // Set template
                'template_file' => '/templates/JSON/theme-manager.json',

                // Auto create if doesn't exist
                'auto_create'=>true,

                // Automatically load file
                'auto_load'=>true

            )
        ));



    }


}