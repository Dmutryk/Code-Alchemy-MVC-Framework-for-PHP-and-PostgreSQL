<?php

namespace Code_Alchemy\Controllers;

use Code_Alchemy\Content\Page_Title;
use Code_Alchemy\Controllers\Helpers\Controller_Key;
use Code_Alchemy\Controllers\Helpers\Missing_Layout_Creator;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Directors\Forms_Submissions_Director;
use Code_Alchemy\Security\Officer;
use Code_Alchemy\core\application_state;
use Handlebars\Loader\FilesystemLoader;
use Handlebars\Loader\StringLoader;

/**
 *  Project: X-Objects web application framework
 *  Module: Controllers and page rendering
 *  Component: Controller
 *  Purpose: Control page rendering
 */
abstract class Application_Controller extends Alchemist{

    /**
     * @var array of Data available for View/Layout
     */
    protected $data = array();

    /**
     * @var string the View Key
     */
    protected $view_key = '';

    /**
     * @var string the theme, for use with xCMS
     */
    protected $theme = '';

    /**
     * @var string Theme Root, when including a Theme
     */
    protected $theme_root = '';

    /**
     * @var string Layout to be used when rendering the View
     */
    protected $layout = '';

    /**
     * @var array of Page Variables
     */
    protected $page_vars = array();

    /**
     * @var \xo_resource_bundle Bundle of Resources
     */
    protected $resources = null;

    /**
     * @var string Path Root
     */
    public $pathroot = "";

    /**
     * @var bool true to debug component
     */
    private $debug = false;

    /**
     * @return string theme root, used by Layout
     */
    public function theme_root(){

        return strlen( $this->theme_root )? $this->theme_root: '/themes/'.$this->theme.'/';

    }

    /**
     * Render a view by key name
     * @param $key
     * @param null $vars
     * @param string $lang
     */
    protected function render( $key,$vars = null,$lang = 'en_US' ){

        global $webapp_location,$container;

        $this->view_key = $key;

 	 	// load resources
        if ( file_exists( $webapp_location."/app/resources/$lang/$key.php"))
              require_once($webapp_location."/app/resources/$lang/$key.php");

        /**
         * Set the Layout, to wrap the View
         */
        $this->set_layout( $lang );


    }

    /**
     * @param string $layout_file
     */
    private function require_layout( $layout_file ){

        // Require Once
        require_once $layout_file;

    }

    /**
     * Set the layout
     * @param string $lang
     */
    private function set_layout( $lang ){

        //if ( $this->debug ) \FB::info(get_called_class().": Setting layout for $lang");

        // Get the Instance
        $container = Code_Alchemy_Framework::instance();

        if ( $this->layout ) {

            // if using a theme, check there for the layout
            if ( strlen( $this->theme )){

                $layout = $container->webroot().$this->theme_root().$this->layout.".php";

                if ( $this->debug ) \FB::info($layout);

                if ( file_exists($layout))

                    $this->require_layout($layout);

                else {

                    // New!  Check if 404 layout exists
                    $layout = $container->webroot().$this->theme_root()."404.php";

                    if ( ! file_exists($layout))

                        new Missing_Layout_Creator($this->layout);


                    if ( file_exists($layout))

                        $this->require_layout($layout);

                    else {

                        if ( $this->debug ) \FB::error(get_called_class().": $layout: This layout doesn't exist");

                        echo "
<html>
    <head>
        <title>Code_Alchemy Framework: A Fatal Error has occurred</title>
    </head>
    <body>
    <div style='font-family: Verdana,Helvetica,sans-serif;font-size: 20pt; padding: 10px; margin: 20px auto; width: 700px; height: auto; overflow:hidden ;min-height: 300px; background-color: #565656; color: white'><p>Code_Alchemy says:</p> <p>'Something has not gone well here.</p> <p></p> I simply can't load the Layout <span style='color:#90ee90;'>$layout</span>.</p><p style='font-size: 8pt'>Hopefully this is helpful, but if not, please email <a style='color: lightgreen; font-weight: bold' href='mailto:support@x-objects.org'>Our Support Team</a></p> </div>
    </body>
</html>
                        ";

                    }

                }


            } else {

                $layout = $container->webroot(). "/app/views/layouts/".$this->layout.".php";

                if ( ! file_exists($layout))

                    new Missing_Layout_Creator($this->layout);


                if ( file_exists( $layout))

                    $this->require_layout($layout);

            else {

                \FB::error(get_called_class().": $layout: This layout does not exist");

                global $codealchemy_location;
                /**
                 * Check if the layout is in X-Objects
                 */

                $layout = $codealchemy_location . "views/layouts/" . $this->layout . ".php";

                if ( ! file_exists($layout) )

                    new Missing_Layout_Creator($this->layout);

                if ( file_exists($layout) )

                    $this->require_layout($layout);

                else {

                    \FB::error(get_called_class().": Fatal: $layout: Layout not found, and unable to create");
                }


            }

        }

    }

    }
 	
 	/**
 	 * magic get for standard stuph
 	 * @param string $what key of what to get
 	 * @return mixed the stuff or null if nothing found
 	 */
 	 public function __get( $what){
          global $container,            // x-object instance
                 $webapp_location,      // root of this webapp
                 $controller_name;      // name of current controller
 	 	switch( $what ){
            case 'controller_name': return get_called_class(); break;
              case 'web_location': return $webapp_location; break;
              case 'ses': case 'session': return new \SESSION; break;
              case 'files': return new \FILES; break;
              case 'container': return $container; break;
 	 		case 'req': case 'request': return new \REQUEST; break;
 	 	case 'uri': return new REQUEST_URI(); break;
            /**
             * By default we get the page var with the same name
             */
        default:
            return isset($this->page_vars[$what])?$this->page_vars[$what]:null;
        break;
 	 		
 	 	}
 	 }

 	 public function is_active( $key ) { return $this->view_key == $key? "active":"";}

     public function __construct(){
         global $webapp_location;
         $this->pathroot = $webapp_location;
         $this->resources = new \xo_resource_bundle("a_controller");

     }
     // public access to page vars
     public function page_vars(){
         return $this->page_vars;
     }

     /**
      * Set a Page Var to a specific value (a convenience method)
      * @param $page_var string the var to set
      * @param $value mixed the value
      */
     public function __set($page_var,$value){
        $this->page_vars[$page_var] = $value;
     }

    /**
     * @return REQUEST_URI
     */
    public function uri(){ return new REQUEST_URI(); }


    /**
     * @return bool true if a user is currently logged in
     */
    public function is_logged_in(){

        return !! (new Officer())->is_admitted();

    }

    /**
     * @return bool true if this is an admin user
     */
    public function is_admin(){

        $me = (new Officer())->me();

        return !! ($me->type =='admin'|| $me->user_type =='admin' );


    }

    /**
     * @return bool true if current user is a Customer user
     */
    public function is_customer(){

        $type = \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->security_manager()->me()->type;

        return !! (in_array($type,array('admin','customer')));

    }

    /**
     * @return bool true if this is afacebook user
     */
    public function is_facebook_user(){

        $dynamic_Model = (new Officer())->me();

        return !! ( $dynamic_Model->facebook_id);


    }
    /**
     * @return bool true if user is verified
     */
    public function is_verified(){

        $dynamic_Model = (new Officer())->me();

        return $dynamic_Model->is_verified || $dynamic_Model->facebook_id;

    }

    protected function redirect_handler(){

        \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->security_manager()->redirect_handler();


    }

    /**
     * @return array of Data for View/Layout
     */
    public function data(){

        return $this->data;
    }

    /**
     * @return \stdClass representation of Data
     */
    public function data_as_object(){

        $object = new \stdClass();

        foreach ($this->data as $key => $value)

            $object->$key = $value;

        return $object;

    }

    /**
     * @return Array_Object Scope of Controller
     */
    public function scope(){

        return new Array_Object( $this->data );

    }

    /**
     * @param null $templates_directory
     * @param bool|false $compile_from_string
     * @return \Handlebars\Handlebars
     */
    public function handlebars_engine(
        $templates_directory = null,
        $compile_from_string = false

    ){

        $templates_directory = $templates_directory ? $templates_directory : Code_Alchemy_Framework::instance()->webroot()."/templates/";

        return new \Handlebars\Handlebars(array(

            'loader' => $compile_from_string ?

                new StringLoader():

                new \Handlebars\Loader\FilesystemLoader( $templates_directory ),

            'partials_loader' => new \Handlebars\Loader\FilesystemLoader($templates_directory)
        ));

    }

    /**
     * @param string $template_name
     * @param array $data
     * @return string templatized data
     */
    public function handlebars( $template_name , array $data ){

        return $this->handlebars_engine()->render($template_name,$data);

    }

    public function handlebars_string( $template_text, array $data ){

        return $this->handlebars_engine(null,true)->render($template_text,$data);


    }

    /**
     * @return string Page Title
     */
    public function page_title(){

        return (string) new Page_Title();

    }

    /**
     * Handle User forms
     * @param array $data
     * @return array
     */
    public function handle_forms( array $data = array()){

        $handler = new Forms_Submissions_Director(
            (count($data)>0?$data: $_POST)
        );

        return $handler->process_submissions();

    }

    /**
     * @return application_state for application
     */
    public function state(){

        return new application_state( $this->uri()->part(1) );

    }

    /**
     * @return object User
     */
    public function user(){

        return Officer::fetch()->me();

    }

    /**
     * @param string $name of layout
     * @return bool true iff layout file exists
     */
    public function layout_exists( $name ){

        global $webapp_location;

        $layout_file = $webapp_location."/app/views/layouts/$name.php";

        if ( $this->theme )

            $layout_file = $webapp_location.$this->theme_root.$name.".php";

        return !! file_exists($layout_file);
    }

    /**
     * @return string Controller key
     */
    public function key(){

        return (string) new Controller_Key();

    }


}
?>
