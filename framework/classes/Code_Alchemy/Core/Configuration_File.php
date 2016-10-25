<?php


namespace Code_Alchemy\Core;


use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\JSON\JSON_File;

class Configuration_File extends JSON_File {

    /**
     * Create a new instance
     */
    public function __construct(){

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();

        parent::__construct( array(

            // Set path
            'file_path' => $webapp_location.'/app/config/code-alchemy.json',

            // Set template
            'template_file' => '/templates/JSON/code-alchemy.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            // Automatically load file
            'auto_load'=>true,

            'string_replacements' => array(

                '/__namespace__/' => (string) new Namespace_Guess( true ),
            )

    ));



    }

    /**
     * @return bool true if we should auto-create dynamic models
     */
    public function auto_create_models(){

        return !!( isset( $this->find('models')['auto-create-dynamic-models']) &&
            $this->find('models')['auto-create-dynamic-models']);

    }

    /**
     * @return bool true if this is development
     */
    public function is_development(){

        $is_Development = !!($this->find('run-mode') == 'development');

        //\FB::info("Is Development? ".($is_Development?"yes":"no"));

        return $is_Development;

    }

    /**
     * @return string Default From Email
     */
    public function default_from_email(){

        $email = 'no-reply@'.$_SERVER['HTTP_HOST'];

        $messaging = $this->find('messaging');

        if ( $messaging && is_array($messaging))

            $email = @$messaging['default-from'];

        return $email;
    }

    /**
     * @return string language
     */
    public function language(){

        return ($this->find('language')? $this->find('language'):'en');
    }

    /**
     * @return bool true if MLS is enabled
     */
    public function is_mls_enabled(){

        return !! @$this->find('multi-language-support')['enabled'];

    }

    /**
     * @return array Production Hosts
     */
    public function production_hosts(){

        return is_array($this->find('production-hostnames'))?

            $this->find('production-hostnames'): [];
    }

}