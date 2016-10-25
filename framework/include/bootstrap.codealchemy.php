<?php

/**
 * Class Code_Alchemy_Bootstrapper
 * @package Code_Alchemy
 */
class  Code_Alchemy_Bootstrapper {

    /**
     * @var string Code_Alchemy Root Directory
     */
    private $parnassus_root = '';

    /**
     * @var array of user-added paths
     */
    private $added_paths = array();

    /**
     * @var bool if true, the Bootstrap process runs in Debug mode.
     * This can produce very verbose output!
     */
    private $debug = false;

    /**
     * @var string Full path location for Code_Alchemy
     */
    private $xloc = '';         // default x-objects location

    /**
     * @var string Location for current running web application
     */
    private $wloc = '';         // webapp location

    /**
     * @var bool true to bypass throwing an exception when a class is not found
     */
    private $bypass_exception = false;


    private $platform = '';
    private $separator = '';
    private $php_self = '';
    // connection xml
    private $conn_xml = null;

    // x-objects classes directories
    private $xclass_dirs = array(
        'twitter',
        'vendors','vendors/phmagick', 'vendors/Handlebars',
        'vendors/Handlebars/Loader','vendors/Handlebars/Cache','vendors/Handlebars/Helper');


    /**
     * Create a new Bootstrap Object
     */
    public function __construct(){


        global
            $codealchemy_location,
            $autoload_bypass_exception,
            $codealchemy_location,
            $webapp_location;

        // Set Code_Alchemy Root
        $this->parnassus_root = $codealchemy_location?$codealchemy_location:$this->guessed_location();

        // Set bypass Exception
        $this->bypass_exception = $autoload_bypass_exception;

        // Set the platform
        $this->platform = preg_match( '/;/' , ini_get( "include_path" ) ) ? "win" : "ux";

        // Get the path separator
        $this->separator = $this->platform == 'win' ? ';' : ':';

        // Set the Webapp Location
        $this->wloc = $webapp_location;

        // Set PHP Self
        $this->php_self = $_SERVER['PHP_SELF'];

        $this->xloc = $codealchemy_location;


        if ( ! $this->xloc){

            $this->xloc = $this->guessed_location();

        }

    }

    public function go(){

    }

    // function to obtain all possible paths to find classfiles
	private function paths() {

        $platform = $this->platform;

        $separator = $this->separator;

        $pathroot = $this->xloc;

        $paths = $pathroot . "/classes";

        $paths .= $separator . $pathroot . "classes".
            $separator . "$this->wloc/app/models" .
            $separator . "$this->wloc/app/classes";


        foreach ($this->xclass_dirs as $dir)
            $paths .= $separator.$pathroot."/classes/$dir";

        // handle modules
        $modules = null;
        if ( isset( $xml ) && $xml->modules )
            $modules = $xml->modules->children();
        if ( $modules )
            foreach ( $xml->modules->children() as $module )
                $paths .= $separator . $pathroot . $module . "/classes";

        /**
         * New! v1.3.0 may add some new custom paths
         */
        foreach ( $this->added_paths as $path)
            $paths .= $separator. $path;

        return $paths;
    }

    /**
     * Auto Load a class given its name, which may also include one
     * or more namespaces
     *
     * @param $classname string the full name of class to load
     * @return bool true if found
     * @throws Exception
     */
    public function autoload($classname){

        // support for namespaces
        $parts = explode('\\',$classname);

        $classname = array_pop($parts);

        // New fast method
        $candidate = $this->guessed_location().'/classes/'.implode('/',$parts).'/'.$classname.".php";

        //echo "candidate = $candidate<br>\r\n";

        if ( file_exists($candidate ) ) {

            require_once($candidate);

            return true;

        }

        // Try also for local app classes
        $candidate2 = $this->guessed_webroot().'/app/classes/'.implode('/',$parts).'/'.$classname.".php";

        //echo "Candidate is $candidate2><br>\r\n";

        if ( file_exists($candidate2 ) ) {

            require_once($candidate2);

            return true;

        }

        $path = explode( $this->separator, $this->paths() ); //get all the possible paths to the file (preloaded with the file structure of the project)

        foreach($path as $tryThis) {
            $candidate = $tryThis . '/' . $classname . '.php';
            if ( $this->debug ) echo "_autoload(): I am looking to see if $candidate is a file that exists. <p>\r\n";
            $exists = file_exists( $candidate );
            if ($exists) {

                if ( $this->debug ) echo "_autoload(): <span style='color:green'>Success!</span>. Found $candidate and loading it now. <p>\r\n";

                require_once($candidate);
                return true;
            }
        }

        if ( $this->debug ) echo "_autoload(): I am unable to find any valid location for $classname<p>";
        global $autoload_bypass_exception;

        if ( ! $autoload_bypass_exception ) {

            //FB::error("$classname: Code_Alchemy can't find this PHP Class");
            //throw new Exception("$classname</span>: Not a valid Class Name, or unable to find in search path");
        }

        else return false;

    }

    /**
     * @return string Webroot
     */
    private function guessed_webroot(){

        global $webapp_location;

        return $webapp_location;

    }

    /**
     * @return string Guessed Location
     */
    private function guessed_location(){

        $loc = '';

        global $codealchemy_location;

        $loc = $codealchemy_location;

        if ( ! $loc ) {

            $php_self = $_SERVER['PHP_SELF'];

            $parts = explode('/',$_SERVER['PHP_SELF']);

            array_pop( $parts );

            $loc = implode('/',$parts);


            /**
             * For Composer
             */
            if ( ! strlen($loc)){

                $loc = $_SERVER['DOCUMENT_ROOT']."/var/www/code_alchemy/framework/";

            }

        }

        $codealchemy_location = $loc;

        return $loc;
    }


    /**
     * @param string $path to add to the autoloader
     */
    public function register_new_class_path( $path ){

        $this->added_paths[] = $path;

    }
}

$boot = new Code_Alchemy_Bootstrapper();

$xobjects_bootstrapper = $boot;


/*
function __autoload($classname) {
    global $boot;
    return $boot->autoload($classname);
}*/

/**
 * The Code_Alchemy Autoload function
 * @param $classname
 * @return bool
 */
function parnassus_autoload( $classname ) {

    $boot = new Code_Alchemy_Bootstrapper();

    return $boot->autoload($classname);

}

// Register Code_Alchemy' auto loader
spl_autoload_register('parnassus_autoload');


//! get the basename of a file
function xo_basename( $uri = __FILE__ ) { return basename($uri); }

function guessed_location(){
    $slices = explode('/',$_SERVER['PHP_SELF']);
    $loc = implode('/',array_slice($slices,0,count($slices)-1));
    return $loc;
}

/**
 * Is the given element a Model (Business Object)?
 * @param object $object
 * @return bool true if it is
 */
function is_model( $object ){

    return !! ( is_object($object) && is_subclass_of( $object,'business_object') );

}

/**
 * @param string $name of component
 */
function require_theme_component( $name ){

    require_once \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->webroot()."/app/views/components/".
        (string) new \Code_Alchemy\Helpers\Theme_Name_Guess()."/$name.php";

}


?>
