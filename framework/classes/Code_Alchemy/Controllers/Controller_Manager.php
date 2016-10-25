<?php
namespace Code_Alchemy\Controllers;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Application_Context_Name;

/**
 * Class Controller_Manager
 * @package Code_Alchemy\Controllers
 */
class Controller_Manager extends Alchemist {

    /**
     * @var bool true for debugging
     */
    private $debug = false;

    private $router = null;

    private $controller_file = null;

    private $is_routed = false;         // is this a routed controller?

    /**
     * @param bool|false $is_debug
     */
    public function __construct( $is_debug = false ){

        $this->debug = $is_debug;

    }

    /**
     * Load a controller
     */
    public function load_controller(){

        $appname = (string) new Application_Context_Name;

        if ( $this->debug) \FB::info(get_called_class().": Application name is $appname");

        $controller_class = $this->find_controller_class();

        if ( $controller_class ){

            if ( $this->debug) \FB::info(get_called_class().": Controller class is $controller_class");

            $controller = null;

            // fetch the class
            $this->fetch_controller_class($controller_class);


            global $autoload_bypass_exception;

            $autoload_bypass_exception = true;
            // try with and without namespace
            //echo $container->appname;

            if ( class_exists($controller_class))

                $controller = new $controller_class;

            else {

                //if ( $this->is_development()) \FB::info("$controller_class: Class doesn't exist");

                $name ="\\".$appname."\\".$controller_class;
                //echo $name."<br>\r\n";
                if (class_exists( $name))
                {

                    $controller = new $name;

                } else {

                    $name = "\\xobjects\\controllers\\".$controller_class;


                    if ( class_exists( $name)){

                        $controller = new $name;

                    }
                }
            }

            $method = $this->controller_method();

            if ( is_object($controller)) {

                $controller->$method();
            }

            else{

                //if ( $this->is_development() ) \FB::info(get_called_class().": A Dynamic Controller is indicated");

                $controller = new Dynamic_Controller(array_merge($_POST,$_GET));

                $controller->go();

                //echo "<div style='font-family: verdana; background-color: #2f2f2f; color: white; padding: 10px; max-width: 600px; margin: 10px auto; border: 1px red solid;'> $controller_class: X-Objects cannot manufacture a new Object based on this Class.  There may be a problem with resolving Namespaces for the Class</div>";

            }
        }

        $autoload_bypass_exception = false;
    }

    /**
     * Finds the method() to call for the Controller
     * @return string name of method()
     */
    private function controller_method(){

        $method = null;

        if ( $this->router && $this->is_routed)

            $method = $this->router->controller_method($this->key);

        if ( ! $method){

            $parsed_url = parse_url( "http://". $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);

            // now get the parts

            $url_parts = explode('/',$parsed_url['path']);

            $method = isset($url_parts[2]) && strlen($url_parts[2])?$url_parts[2]:'default_action';
        }

        //if ( $this->is_development() ) \FB::info(get_called_class().": Controller Method is $method");

        return $method;
    }


    /**
     * @param \Code_Alchemy\Core\Code_Alchemy_Framework $container
     * @return string Default Controller
     */
    private function default_controller( \Code_Alchemy\Core\Code_Alchemy_Framework $container ){


        return isset( $container->configuration()->site->controllers->default_controller )?
            (string)$container->configuration()->site->controllers->default_controller:'home';

    }
    /**
     * Find the PHP Class needed to load the Controller for the current URL
     * @return string the name of the PHP class to load
     */
    private function find_controller_class(){

        $appname = (string) new Application_Context_Name();

        $class = null;

        global $container;

        $default_controller = $this->default_controller( $container );

       // FB::log($default_controller);

        global $autoload_bypass_exception;
        $autoload_bypass_exception = true;
        $tag = new \xo_codetag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        /**
         * First we see if we can match a Controller without using a Custom Router
         */
        $parsed_url = parse_url( "http://". $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);
        // now get the parts
        $url_parts = explode('/',$parsed_url['path']);
        $this->controller_file = $url_parts[1]?$url_parts[1]:$default_controller;
        $class = $url_parts[1]?$url_parts[1]."_controller": $default_controller. '_controller';

        /**
         * if we couldn't fetch the controller class then keep going
         * with checking for the Router
         */
        if ( ! $this->fetch_controller_class($class)){

            // first see if we have a custom router
            $router_class = $appname."_router";

            //$route = "";
            if ( class_exists($router_class)){

                if ( $container->debug && $container->debug_level > 2 ) echo "$tag->event_format: found a custom router<br>\r\n";
                $this->router = new $router_class($this->key);
                // set class from router
                $this->controller_file = $this->router->controller_file();
                $class = $this->controller_file?"$this->controller_file"."_controller":null;
                if ($class) $this->is_routed = true;    // flag as routed for method()
            } else {

            }
            $autoload_bypass_exception = false;

        }

        //if ( $this->is_development()) \FB::info(get_called_class().": Controller Class is $class");

        return $class;

    }

    /***
     * Fetch a controller class
     * @param string $class to fetch
     * @return bool true if fetched
     */
    private function fetch_controller_class($class){

        $found = false;

        global $webapp_location,$codealchemy_location;

        $file = $webapp_location. "/app/controllers/$this->controller_file.php";


        if ( file_exists( $file)){

            $found = true;
            require_once( $file);
        }

        $file = $codealchemy_location. "controllers/$this->controller_file.php";

        if ( ! $found && file_exists( $file)){
            $found = true;
            require_once( $file);
        }

        //if ( $this->is_development() ) \FB::info(get_called_class().": Controller Class found? ".($found?'yes':'no'));
        return $found;
    }
}