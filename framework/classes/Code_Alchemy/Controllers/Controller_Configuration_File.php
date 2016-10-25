<?php


namespace Code_Alchemy\Controllers;


use Code_Alchemy\Controllers\Helpers\Controller_Key;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\JSON\JSON_File;
use Code_Alchemy\Themes\Helpers\Theme_Root;

class Controller_Configuration_File extends JSON_File {

    public function __construct(){

        global $webapp_location;

        parent::__construct(array(

            // Set path
            'file_path' => $webapp_location.'/app/config/controllers.json',

            // Set template
            'template_file' => '/templates/JSON/controllers.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            'string_replacements'=> array(
                '/__theme_name__/'=>(string) new Theme_Name_Guess(),
                '/__theme_root__/'=>(string) new Theme_Root('',(string)new Theme_Name_Guess())
            )

        ));
    }

    /**
     * @return array of data
     */
    public function get_route(){

        $key = (string) new Controller_Key();

        //if ( $this->is_development() ) \FB::info( get_called_class(). ": Controller Key is $key");

        $route = isset($this->data['routes'][$key]) ? $this->data['routes'][$key] :

            (isset($this->data['routes']['*']) ? $this->data['routes']['*'] : array());

        // For aliases
        if ( is_string($route) && preg_match('/alias:([a-z\_\-]+)/',$route,$hits)){

            if ( $this->is_development() )

                \FB::info(get_called_class().": Found an alias route ".$hits[1]);

            if ( isset($this->data['routes'][$hits[1]])){

                $route = $this->data['routes'][$hits[1]];

            }


        }


        // Normalize Layout
        if ( @$route['layout'] == '*') $route['layout'] = $key;

        return $route;

    }

    /**
     * @return Controller_Settings
     */
    public function settings(){

        $settings = is_array($this->find('settings'))?$this->find('settings'):[];

        return new Controller_Settings($settings);

    }

}