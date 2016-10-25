<?php


namespace Code_Alchemy\Database;


use Code_Alchemy\JSON\JSON_File;

class Database_Configuration_File extends JSON_File {

    /**
     * @var string Default connection
     */
    private static $default_connection = '';

    /**
     * Create a new instance
     */
    public function __construct(){

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();

        parent::__construct( array(

            // Set path
            'file_path' => $webapp_location.'/app/config/database.json',

            // Set template
            'template_file' => '/templates/JSON/database.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            // Automatically load file
            'auto_load'=>true

        ));

        // Iterate all data to find main conn
        foreach ( $this->data as $key => $connection )

            if ( isset( $connection['is_main']) && $connection['is_main'])

                self::$default_connection = $key;


    }

    /**
     * @return object with connections configured
     */
    public function is_configured(){

        foreach($this->data as $key => $connection):

            $cont_full = 0;

            if ( is_array($connection)){

                foreach($connection as $value):
                    if(!empty($value)) $cont_full += 1;
                endforeach;

                if($cont_full != sizeof($connection)) unset($this->data[$key]);

            }

        endforeach;


        return $this->data;

    }

    /**
     * @param array $configuration
     * @return bool true if db is valid
     */
    private function valid_database( array $configuration ){

        $result = true;

        foreach ( array('username','database','hostname') as $member )

            if ( ! @strlen( $configuration[ $member ] )){

            $result = false;

            self::$last_error = "$member: Cannot be empty";

            break;

        }

        return $result;
    }

    /**
     * @param array $configuration
     * @return bool true if set
     */
    public function set_database( array $configuration ){

        if ( ! $this->valid_database($configuration))

            return false;

        foreach ($configuration as $member=>$value )

            $this->set($member,$value);

        return $this->update();

    }

    /**
     * @return string default connection type
     */
    public function default_connection_type(){

        return self::$default_connection;

    }

    /**
     * @return bool true if postgres by default
     */
    public function is_postgres(){

        return $this->default_connection_type() == 'pgsql';

    }



}
