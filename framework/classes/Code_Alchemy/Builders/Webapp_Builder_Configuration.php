<?php


namespace Code_Alchemy\Builders;


use Code_Alchemy\JSON\JSON_File;

class Webapp_Builder_Configuration extends JSON_File {

    /**
     * Create a new instance
     */
    public function __construct(){

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();

        parent::__construct( array(

            // Set path
            'file_path' => $webapp_location.'/app/config/webapp-builder.json',

            // Set template
            'template_file' => '/templates/JSON/webapp-builder.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            // Automatically load file
            'auto_load'=>true

    ));



    }


}