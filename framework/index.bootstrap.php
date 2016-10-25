<?php

namespace Code_Alchemy;

use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Multi_Language_Support\MLS_Manager;
use Handlebars\Autoloader;
use Code_Alchemy\Controllers\Controller_Manager;
use Code_Alchemy\Controllers\Helpers\Controller_Key;
use Code_Alchemy\tools\code_tag;

@session_start();
/**
 * Project: X-Objects MVC framework for PHP and jQuery
 * Author: <david@reality-magic.com> David Owen Greenberg
 * Module: Bootstrap
 * Component: Index Bootstrap File
 *
 * This file is called to load up any page or view in the application.
 */

global $is_debug;

$is_debug = $is_debug ? $is_debug: false;

final class xo_index_bootstrap{

    /**
     * @var bool debugging
     */
    private $debug = false;

    private $req,$ses,$cookie,$uri,$parsed_url,$url_parts;


    //private $key;

    private $app_name;

    /**
     * Create a new Bootstrapper
     */
    public function __construct(){

        global $is_debug;

        $this->debug = $is_debug;

        $this->ses = new \SESSION();

        $this->cookie = new \COOKIE();

        $this->uri = new REQUEST_URI();

        $this->parsed_url = parse_url( "http://". $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);

        // now get the parts
        $this->url_parts = explode('/',$this->parsed_url['path']);


        if ( $is_debug ) \FB::info(get_called_class().": Debugging");

    }

    public function go(){

        global $container;

        $tag = new code_tag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        try {

            //$container = x_objects::instance();

            $container = \Code_Alchemy\Core\Code_Alchemy_Framework::instance();

        } catch ( \Exception $e ) {

            echo '<span style="color:red;">X-Objects bootup failed: '.$e->getMessage().'</span>';

        }

        $this->app_name = $container->appname;

        // Get configuration file
        $config = (new Configuration_File());

        $manager = new Controller_Manager($this->debug);

        $manager->load_controller();

        if ( $container->debug ) \FB::info("$tag->firebug_format: container SINGLETON created");

   }
}

// bootstrap file
$container = null;

// get webroot and pathroot
global $webroot, $pathroot, $directory_name,$codealchemy_location;

$tag = new code_tag(xo_basename(__FILE__),__LINE__,"","");

$request_uri = $_SERVER['REQUEST_URI'];

// load global functions
require_once( $codealchemy_location."include/xo_functions.php");

// New! Require autoloader for Handlebars
require $codealchemy_location. 'classes/vendors/Handlebars/Autoloader.php';

// Register Handlebars Auto loader
Autoloader::register();

// create the bootstrap file
try {

    $bootstrap = new xo_index_bootstrap();

    // run it
    $bootstrap->go();

} catch (Exception $e){

    echo "Fatal Exception: ".$e->getMessage();

}


?>