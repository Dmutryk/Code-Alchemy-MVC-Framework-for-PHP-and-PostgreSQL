<?php


namespace Code_Alchemy\JSON;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;

class JSON_File extends Alchemist {

    /**
     * @var string Template file for this configuration,
     * relative to Code_Alchemy Root
     */
    private $template_file = '';

    /**
     * @var string Path to where the file lives
     */
    private $file_path = '';

    /**
     * @var bool true to auto-create file if doesn't exist
     */
    private $auto_create = false;

    /**
     * @var bool true to automatically load file
     */
    private $auto_load = true;

    /**
     * @var array of data from the file
     */
    protected  $data = array();

    /**
     * @var array of String Replacements
     */
    private $string_replacements = array();

    /**
     * @var bool true to log to firerbug
     */
    private $firebug = false;

    /**
     * @var bool true to use Code_Alchemy root for source files
     */
    private $use_codeza_root = true;

    /**
     * @var string Last error from any operation
     */
    public static $last_error = '';

    /**
     * @var \Code_Alchemy\Cache\Fast_Cache|null
     */
    private static $cache = null;

    /**
     * @var bool true to send verbose output
     */
    private $verbose = false;

    /**
     * @param array $options to set
     */
    public function __construct( $options = array() ){

        if ( $this->verbose ) echo "\t".get_called_class().": Constructing\r\n";

        // Initialize the Cache
        if ( ! self::$cache ) self::$cache = new Fast_Cache();

        foreach ( $options as $name => $value )

            if ( property_exists($this,$name) )

                $this->$name = $value;

        // Auto create if necessary
        if ( $this->auto_create )

            $this->auto_create();

        // Auto load if necessary
        if ( $this->auto_load )

            $this->load_data();

    }

    public function reload(){

        $this->load_data( false );
    }

    /**
     * load data
     */
    private function load_data( $use_cache = true ){

        // If in Cache
        $key = (string)new Cache_Key($this->file_path);

        if ( self::$cache->exists($key) && $use_cache )

            // Get it
            $this->data = self::$cache->get( $key );

        else {

            if ( ! file_exists($this->file_path)){

                \FB::error(get_called_class().": $this->file_path: No such file or directory");

                return;

            }

            $contents = @file_get_contents($this->file_path);

            $this->data = json_decode($contents,true);

            // Did we get an error?
            if ( json_last_error() !== JSON_ERROR_NONE )

                \FB::error(get_called_class().": Error loading file $this->file_path: ".json_last_error_msg());


            // Save in Cache
            self::$cache->set( $key, $this->data );

            // After load trigger
            $this->after_load();

        }

    }

    /**
     * @param $section_name
     * @param array $prunes
     * @return $this
     */
    protected function prune_selected( $section_name, array $prunes ){

        $section = $this->find($section_name);

        foreach ( $prunes as $prune )

            unset( $section[ $prune ]);

        $this->set($section_name,$section);

        return $this;

    }

    /**
     * Child class may overwrite this method to have a trigger
     * after loading file
     *
     * Note: only called once!  Not for each cache load!
     */
    protected function after_load(){


    }

    /**
     * Automatically create Configuration file if it doesn't exist
     */
    private function auto_create(){

        if ( $this->firebug ) \FB::info(get_called_class().": auto creating file");

        $root = $this->use_codeza_root ? (string)new Code_Alchemy_Root_Path() : '';

        $filename = $root . $this->template_file;

        if ( file_exists($filename) && ! file_exists($this->file_path)) {

            $copier = new Smart_File_Copier($filename,$this->file_path,$this->string_replacements,false);

            $copier->copy();

        }



    }

    /**
     * @param $key
     * @param bool $as_object
     * @return mixed|null
     */
    public function find( $key, $as_object = false ){

        return isset( $this->data[$key])?

            ($as_object? json_decode(json_encode($this->data[$key]),false):
                $this->data[$key]):null;
    }

    /**
     * Set a Key value
     * @param $key
     * @param $value
     * @return $this
     */
    public function set( $key, $value ){

        $this->data[ $key ] = $value;

        // For chaining commands
        return $this;

    }

    /**
     * @param $section_name
     * @param $value
     * @param bool $distinct_values
     * @param null $key
     * @return $this
     */
    public function append(

        $section_name,

        $value,

        $distinct_values = false,

        $key = null

    ){

        $section = isset( $this->data[ $section_name ])?

            $this->data[ $section_name ] :

                array();


        if ( ! $distinct_values || ! in_array($value,$section)){

            if ( $key ) $section[ $key ] = $value;

            else $section[] = $value;

        }




        $this->data[ $section_name ] = $section;


        // For chaining
        return $this;

    }

    /**
     * @return bool true if updated
     */
    public function update(){

        if ( ! $this->is_development() ){

            //\FB::info(get_called_class().": Not writing changes, since we are not in development mode.");

            return false;
        }

        $result = @file_put_contents($this->file_path,json_encode($this->data,JSON_PRETTY_PRINT));

        if ( ! $result ) \FB::error(get_called_class().":$this->file_path: Unable to write file");

        return (bool) $result;

    }

    /**
     * @return object
     */
    public function as_object(){


        return json_decode(file_get_contents($this->file_path),false);



    }

    /**
     * Allows user to fetch members directly as though they were properties
     * @param $member
     * @return mixed|null
     */
    public function __get( $member ){

        return $this->find($member);

    }

}