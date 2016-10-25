<?php

namespace Code_Alchemy\Applications\Toolboxes;

use Code_Alchemy\AngularJS\AngularJS_Director;
use Code_Alchemy\AngularJS\Izers\AnglarJSIzer;
use Code_Alchemy\Applications\Dynamic_Console;
use Code_Alchemy\Applications\Shopping_Experience_Module;
use Code_Alchemy\Applications\Toolboxes\Helpers\Text_Colorizer;
use Code_Alchemy\Applications\Web_Director;
use Code_Alchemy\Builders\Photo_Galleries_Builder;
use Code_Alchemy\Builders\Revolution_Slider_Builder;
use Code_Alchemy\Builders\Service_Builder;
use Code_Alchemy\Builders\Webapp_Builder;
use Code_Alchemy\Builders\Website_Images_Builder;
use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\Creators\Create_Models_From_JSON;
use Code_Alchemy\Creators\Database_Table_Creator;
use Code_Alchemy\Creators\Server_Model_Creator;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Directors\Theme_Director;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Specialists\Application_Refresher;


/**
 * Installer Class for X-Objects.  Usually invoked by command-line tool, but could also be used
 * as part of a Web Installation
 */

class Command_Line {

    /**
     * @var bool if true, run in debug mode
     */
    private $debug = false;

    /**
     * @var bool if true, send verbose output
     */
    private $verbose = true;

    /**
     * @var array of switches from command line
     */
    private $switches = array();

    /**
     * @var null database object
     */
    private $database = null;

    /**
     * @var string X-Objects XML file, full path
     */
    private $xml_file = '';

    /**
     * @var \SimpleXMLElement xml configuration
     */
    private $xml_config = null;

    /**
     * @var string working directory
     */
    private $working_dir = '';

    /**
     * @var string root directory
     */
    private $root = '';

    /**
     * @var array of themes supported natively by Code_Alchemy
     */
    private $supported_themes = array( 'angle','flati');

    /**
     * Construct a new Installer
     */
    public function __construct( array $argv ){

        if ( isset( $argv[0])){

            // get the xobjects root
            preg_match( '/^(.*)codealchemy\.php$/',$argv[0],$matches);
            $this->root = $matches[1];

        } else {

            global $codealchemy_location;

            $this->root = $codealchemy_location;

        }


        // parse any switches
        $this->parse_switches( $argv );

        // Set working dir
        $this->working_dir = isset( $this->switches['directory'])? $this->switches['directory']: getcwd();

        // set Database
        $this->database = new Database(  );

    }

    public function info( $nothing, $root, $argv ){

        echo "Ok, here's what I know (or think I know!):\r\n\r\n";

        echo "\tApp Namespace:\t".new Namespace_Guess()."\r\n\r\n";

        //var_dump((new Theme_Manager_Configuration())->find('replacements'));

    }

    /**
     * Access the toolkit
     * @param $what
     * @param $root
     * @param $argv
     */
    public function tools( $what, $root, $argv ){

        new Toolkit($what,$root,$argv);

    }

    /**
     * Make settings and configurations adjustments
     * @param string $what to set
     * @param string $root of application
     * @param array $argv from command line
     */
    public function set( $what, $root, $argv){

        switch( $what ){

            case 'bootstrap-theme':

                $name = @$argv[3];

                if ( ! $name )

                    echo "Usage: xobjects set bootstrap-theme <theme-name>\r\n\r\n";

                else {

                    $name = strtolower($name);

                    $source = $this->root."templates/css/bootstrap.$name.css";

                    $min_src = $this->root."templates/css/bootstrap.$name.min.css";

                    if ( file_exists( $source ))

                        if ( copy( $source, $this->working_dir."/css/bootstrap.css"))

                            echo "Successfully copied bootstrap.css\r\n";


                    if ( file_exists( $min_src))

                        if ( copy( $min_src, $this->working_dir."/css/bootstrap.min.css"))

                            echo "Successfully copied minified bootstrap.min.css\r\n";


                }


            break;

            default:

                echo "Usage: xobjects set bootstrap-theme\r\n";

            break;

        }

    }

    /**
     * Angular Director
     */
    public function ng( $a , $b, $c ){

        $director = new AngularJS_Director( $a, $b, $c );

        $director->process_command();

    }

    /**
     * Inject the REST API method into an existng Controller
     * @param string $controller_file
     */
    private function inject_rest_api( $controller_file ){

        // Get string
        $contents = file_get_contents( $controller_file );

        // If method already exists
        if ( preg_match('/rest_api/',$contents) || preg_match('/function v1\(\)/',$contents))

            echo "Code_Alchemy: It appears the REST API is already in your api Controller.\r\n";

        else {

            $new_contents = '';

            $file = fopen( $controller_file,'r');

            while ( $line = fgets( $file )){

                // Add to new Contents
                $new_contents .= $line;

                // Is this the insertion point?
                if ( preg_match('/api_controller/',$line))

                    // Append the method
                    $new_contents .= preg_replace('/_mynamespace_/',$this->guess_namespace(),file_get_contents($this->root."/templates/fragments/rest_api_method.php"));

            }

            fclose( $file );

            file_put_contents($controller_file,$new_contents);

            echo "Code_Alchemy: We've injected the API method into your existing Controller.\r\n";

        }

    }

    /**
     * Add the REST API to an existing application
     */
    private function add_rest_api(){

        $source_api_controller =
            $this->root."templates/controllers/api.php";

        $dest_api_controller =
            $this->working_dir."/app/controllers/api.php";

        if ( file_exists($dest_api_controller)){

            echo "Code_Alchemy: Notice: It appears you already have a controller called api.php\r\n";

            // Inject REST API if needed
            $this->inject_rest_api( $dest_api_controller );


        }
        else {

            if ( $this->copy_and_replace($source_api_controller,$dest_api_controller,array(
                "/\_mynamespace\_/" => $this->guess_namespace(),)))

                echo "Code_Alchemy:  Successfully added the REST API to your application\r\n";

            else echo "Code_Alchemy: Error: Unable to copy $source_api_controller to $dest_api_controller\r\n";
        }


    }

    /**
     * Theme ize
     */
    public function themeize(){

        // Last step, we want to call Theme Director
        $director = new Theme_Director((string) new Theme_Name_Guess(),$this->switches);

        $director->analyze_and_bind($this->verbose);

        // As of PHP 5.4.0, REQUEST_TIME_FLOAT is available in the $_SERVER superglobal array.
// It contains the timestamp of the start of the request with microsecond precision.
        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

        echo (string) new Text_Colorizer("\tThemization took ".number_format($time,2) ." seconds to complete!\r\n",'light_cyan');

        if ( $this->verbose ) echo "\r\nThemization is complete!\r\n\r\n";

    }


    /**
     * Theme ize
     */
    public function angularjsize(){

        // Last step, we want to call Theme Director
        $director = new AnglarJSIzer(array_merge($this->switches,array(

            'verbose'=>'yes'

        )));

        $director->execute();

        // Get time
        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

        echo (string) new Text_Colorizer("\tAngularJSization took ".number_format($time,2) ." seconds to complete!\r\n",'light_cyan');

        if ( $this->verbose ) echo "\r\nAngularJSization is complete!\r\n\r\n";

    }


    /**
     * Add Components to your Application
     * @param string $what to add
     * @param string $root of X-Objects
     * @param array $argv from command line
     */
    public function add( $what, $root, $argv ){

        $builder = null;

        switch ( $what ){

            case 'shopping':

                $module = new Shopping_Experience_Module();

                $module->set_options($this->switches);

                $module->deploy( true );

            break;

            case 'web-director':

                $this->add_console();


                break;

            case 'photo-galleries':

                $builder = new Photo_Galleries_Builder();

            break;

            case 'revolution-slider':

                $this->switches['references'] = 'selectable_image';

                $builder = new Revolution_Slider_Builder();
                break;

            case 'website-images':

                // Get a builder
               $builder = new Website_Images_Builder();


            break;

            case 'modal':

                $this->add_modal(@$argv[3]);

            break;

            case 'customer-portal':

                $this->add_portal();

            break;

            case 'login-system':

                $this->add_login_system();

            break;

            case 'rest-api':

                $this->add_rest_api();

            break;

            case 'bootstrap-components':

                $this->add_bootstrap_components();

            break;

            case 'client-validation':

                $file = $this->root."js/xo-validate.js";

                if ( file_exists( $file ) )

                    if ( copy( $file, $this->working_dir."/js/xo-validate.js") )

                        echo "Client validation has been added to your application!\r\n\r\n";

                    else

                        echo "Error: Unable to add Client Validation.\r\n\r\n";


            break;

            default:

                echo "Usage: xobjects add <component>\r\n
Possible Components:\r\n
\tweb-director\t\tAdds (or upgrades) Web Director 2\r\n
\tshopping\t\tAdds (or upgrades) Shopping Experience Module\r\n
\trevolution-slider\tAdds support for a Revolution Slider\r\n
\twebsite-images\t\tAdds support for saving website images\r\n
\tphoto-galleries\t\tAdds support for photos with categories\r\n\r\n";

            break;
        }


        // If we got a Builder
        if ( $builder) {

            $builder->set_options($this->switches );

            if ($builder->build(true))

                echo "The selected component was build successfully\r\n\r\n";

            else

                echo "Error: ".$builder->error."\r\n\r\n";
        }

    }

    /**
     * @param string $layout_name
     */
    private function add_modal( $layout_name ){

        if ( ! $layout_name )

            echo "Usage: parnassus add modal <layout_name>\r\n\r\n";

        else {

            $candidate_layout = "$this->working_dir/app/views/layouts/$layout_name.php";

            $layout_exists = false;

            if ( ! file_exists( $candidate_layout )){

                echo "$candidate_layout: This layout doesn't exist\r\n";

                foreach ( $this->supported_themes as $theme ){

                    $candidate_layout = "$this->working_dir/themes/$theme/$layout_name.php";

                    if ( ! file_exists($candidate_layout))

                        echo "$candidate_layout: This layout doesn't exist\r\n";

                    else {

                        $layout_exists = true;

                        echo "$candidate_layout: Found layout!\r\n";

                    }
                }

            } else {

                $layout_exists = true;

                echo "$candidate_layout: Found layout!\r\n";

            }

            if ( $layout_exists ){

                $resulting_content = '';

                $modal_type = isset($this->switches['type'])?$this->switches['type'].'-modal':'modal';

                $modal_file = "$this->root/templates/misc/$modal_type.php";

                $id = isset($this->switches['id'])?($this->switches['id']):(string)new Random_Password(10);

                $modal_string = preg_replace('/__id__/',$id,file_get_contents($modal_file));

                $handle = fopen( $candidate_layout,'r');

                while ( $line = fgets($handle) ){

                    if ( preg_match('/<\/body>/',$line))

                        $resulting_content .= $modal_string."\r\n".$line;

                    else

                        $resulting_content .= $line;

                }

                fclose($handle);

                file_put_contents($candidate_layout,$resulting_content);

                echo "Code_Alchemy: Ok, we added a Modal to your Layout\r\n\r\n";
            }

        }
    }

    /**
     * Add the Code_Alchemy Customer Portal
     */
    private function add_portal(){

        echo "Code_Alchemy: Excellent, let's add the Customer Portal.\r\n";

        // Step 1: Get the Code_Alchemy Portal Controller
        $this->create_controller('parnassus_portal','parnassus_portal');

        // Step 2: Add the Code_Alchemy Portal Layouts
        foreach ( array( 'parnassus-portal',) as $l ){

            $layout = $this->root."/templates/views/layouts/$l.php";

            $dest = $this->working_dir."/app/views/layouts/$l.php";

            $this->copy_and_replace($layout,$dest,array(

                '/_mynamespace_/'=>$this->guess_namespace()

            ));

        }


        echo "Code_Alchemy: You are rich, you can attract into your life everything you want or need, to be successful.\r\n";

        // Step 3: Add components
        foreach ( array('parnassus-portal-footer',  'parnassus-portal-head','parnassus-portal-nav') as $component ){

            $file  = $this->root."/templates/views/components/$component.php";

            $dest = $this->working_dir."/app/views/components/$component.php";

            $this->copy_and_replace($file,$dest,array(

                '/_mynamespace_/'=>$this->guess_namespace()

            ));


        }

        echo "Code_Alchemy: That's it!  Now your customers can expect even better service!\r\n\r\n";

    }

    /**
     * Add the Code_Alchemy Dynamic Admin Console to the current application
     */
    private function add_console(){

        echo "Code_Alchemy: Great, I will add they dynamic console to your application\r\n";

        $app = new Dynamic_Console();

        $app->set_options($this->switches);

        $app->deploy($this->verbose);

        echo "Code_Alchemy: All done!\r\n\r\n";

    }

    /**
     * Add the Login System (including Login with Facebook)
     */
    private function add_login_system(){

        $working_dir = $this->working_dir;

        $root = $this->root;

        echo "Code_Alchemy: Ok let's add, or verify, a login system for this app.\r\n";

        // Get the database
        $db = $this->database;

        // Check if User Table exists
        if ( $db->has_table('user'))

            echo "Code_Alchemy: Your database already has a User table.\r\n";

        else {

            echo "Code_Alchemy: Preparing to add User Table.\r\n";

            $this->switches['type'] = 'user';

            $this->create_database_table('user');

        }


        // Check if User Session Table exists
        if ( $db->has_table('user_session'))

            echo "Code_Alchemy: Your database already has a User Session table.\r\n";

        else {

            echo "Code_Alchemy: Preparing to add User Session Table.\r\n";

            $this->create_database_table('user','user_session_table');

        }

        // Get Models
        $this->create_server_model('user');
        $this->create_server_model('user_session');

        $this->create_backbonejs_model('user');
        $this->create_backbonejs_model('user_session');
        $this->create_backbonejs_collection('user');
        $this->create_backbonejs_collection('user_session');

        // For each supported theme
        foreach( $this->supported_themes as $theme ){

            $theme_dir = "$working_dir/themes/$theme/";

            if ( file_exists( $theme_dir) && is_dir($theme_dir) )

                $this->add_login_components_for_theme( $theme );

        }
         echo "Code_Alchemy: All set!\r\n\r\n";

    }

    /**
     * Add login components for a specific theme
     * @param string $theme
     */
    private function add_login_components_for_theme( $theme ){

        // Set a few vars
        $working_dir = $this->working_dir;

        $theme_name = ucfirst( $theme );

        $root = $this->root;

        // Check for Angle theme
        $theme_dir = "$working_dir/themes/$theme/";

        echo "Code_Alchemy: This application uses the $theme_name Theme.\r\n";

        // Add various layouts
        $layouts = array( 'login','acceder');

        foreach ( $layouts as $l){

            $login_layout = "$root/templates/$theme/layouts/$l.php";

            $destination = "$working_dir/themes/$theme/$l.php";

            if ( file_exists( $destination) && ! $this->overwrite_allowed() )

                echo "Code_Alchemy: $l: The Login Layout is already present, use --overwrite=yes to overwrite it.\r\n";

            else

                if ( $this->copy_and_replace($login_layout,$destination,array(
                    '/__namespace__/'=>$this->guess_namespace()
                )))

                    echo "Code_Alchemy: Ok, we added the $theme_name Theme Login Layouts.\r\n";

        }

    }

    /**
     * Reconcile database with the application
     * @param $what
     * @param $root
     * @param $argv
     */
    public function reconcile( $what, $root, $argv ){

        if ( $this->database )

            foreach ( $this->database->tables_and_views() as $table )

                $this->reconcile_table( $table );

        else echo "Warning: Unable to connect to any database\r\n";

    }

    /**
     * @param string $table to reconcile
     */
    private function reconcile_table( $table ){

        // server model
        if ( ! $this->server_model_exists( $table ))

            $this->create_server_model( $table );

        else

            echo "$table: A Server Model already exists for this Table.\r\n";

        // backboneJS model
        if ( ! $this->backbonejs_model_exists( $table ))

            $this->create_backbonejs_model( $table );

        else

            echo "$table: A BackboneJS Model already exists for this Table.\r\n";

        // backboneJS collection
        if ( ! $this->backbonejs_collection_exists( $table ))

            $this->create_backbonejs_collection( $table );

        else

            echo "$table: A BackboneJS Collection already exists for this Table.\r\n";


    }

    /**
     * Create a server model
     * @param $name
     * @param bool $firebug
     * @param string $target
     * @param string $namespace
     * @param string $model_template to use
     */
    public function create_server_model(
        $name ,
        $firebug = false,
        $target = '',
        $namespace = '',
        $model_template = 'model'
    ){

        if ( isset( $this->switches['template']))

            $model_template = $this->switches['template'];

        // Get server model creator
        $creator = new Server_Model_Creator($name,$model_template);

        // Set options
        $creator->set_options($this->switches,get_called_class());

        // if created
        if ( $creator->create($this->verbose))

            echo "The Server Model was successfully created\r\n";

        else

            echo "Error: Unable to create server model: ".$creator->error."\r\n";

    }

    /**
     * @return string Referenced Model Name, for templates
     */
    private function referenced_model_name(){

        $name = '';

        if ( isset( $this->switches['references']))

            $name = $this->switches['references'];

        return $name;
    }

    /**
     * Create a server model
     * @param $name
     */
    private function create_backbonejs_model( $name ){

        // Create hyphenated name
        $hyphenated_name = preg_replace( '/_/','-',$name);

        echo "Code_Alchemy: Creating new BackboneJS Model '$hyphenated_name'\r\n";

        $target = "$this->working_dir/js/models";

        $dest = "$target/$hyphenated_name.js";

        // If already exists, and not allowed to overwrite
        if ( file_exists($dest) && ! $this->overwrite_allowed() ){

            echo "Code_Alchemy: BackboneJS View '$name' already exists.  To overwrite, use --overwrite=yes\r\n";

        } else {

            if ( $this->copy_and_replace( "$this->root/templates/js/models/backbone-model-template.js", $dest,array(
                '/_model_name_/'=>$name,
                '/_api_base_/'=>'api/v1',
            )))

                echo "Code_Alchemy: BackboneJS View '$name' created\r\n";

        }

    }

    /**
     * Create a server model
     * @param $name
     */
    private function create_backbonejs_collection( $name ){

        // Create hyphenated name
        $hyphenated_name = preg_replace( '/_/','-',$name);

        $target = "$this->working_dir/js/collections";

        $dest = "$target/$hyphenated_name.js";

        if ( file_exists($dest) && ! $this->overwrite_allowed())

            echo "Code_Alchemy: BackboneJS Collection '$name' already exists.  To overwrite, use --overwrite=yes\r\n";

        else {

            if ($this->copy_and_replace( "$this->root/templates/js/collections/backbone-collection-template.js", $dest,array(
                '/_model_name_/'=>$name,
                '/_hyphenated_name_/'=>$hyphenated_name,
                '/_api_base_/'=>'api/v1',
            )) )

                echo "Code_Alchemy: Collection '$name' has been created\r\n";

        }
    }


    /**
     * @param $name
     * @return bool true if named server model exists
     */
    private function server_model_exists( $name ){

        return !! file_exists( "$this->working_dir/app/models/$name.php" );

    }

    /**
     * @param $name
     * @return bool true if named server model exists
     */
    private function backbonejs_model_exists( $name ){

        return !! file_exists( "$this->working_dir/js/models/$name.js" );

    }


    /**
     * @param $name
     * @return bool true if named server model exists
     */
    private function backbonejs_collection_exists( $name ){

        return !! file_exists( "$this->working_dir/js/collections/$name.js" );

    }


    /**
     * Show app configuration and other information
     * @param string $what to show
     * @param string $root of X-Objects
     * @param array $argv passed via CLI
     */
    public function show( $what, $root, $argv){

        switch( $what ){

            default:

                echo
"Usage: xobjects show <aspect>\r\n
Acceptable Aspects:\r\n
\tconfiguration\tOutput information about the current application's configuration\r\n
";

            break;
        }

    }

    /**
     * Replace a series of variables with values in a string
     * @param string $string
     * @param array $values
     * @return string resulting replaced string
     */
    private function replace_values_in_string( $string , array $values ){

        foreach ( $values as $regex => $value )

            $string = preg_replace("/$regex/",$value,$string);

        return (string) $string;

    }

    /**
     * Create a database table
     * @param string $name
     * @param string $template
     * @param bool $verbose
     */
    public function create_database_table($name,$template = '', $verbose = false){

        $template = $template?$template: (isset($this->switches['type'])?$this->switches['type']:'base');

        // Get Table Creator
        $creator = new Database_Table_Creator($name,$template);

        // Set options
        $creator->set_options($this->switches,get_called_class());

        if ( $creator->create($this->verbose) );

            //echo "Table $name was successfully created\r\n";

        else{

            \FB::error("Error: $name: Unable to create: ".$creator->error);

        }


    }

    /**
     * @param $base_name
     * @return string Model template for given base
     */
    private function get_model_template( $base_name ){

        echo "Code_Alchemy: Searching for Model Template for $base_name\r\n";

        $template = 'model';

        switch ( $base_name ){
            case 'multimedia':

                $template = 'multimedia';

            break;

            case 'pdf_with_reference':

                $template= 'pdf_with_reference';

            break;

            case 'photo_with_reference':

                $template= 'photo_with_reference';

                break;


            case 'photo':
            case 'image':

                $template = 'photo';

            break;

            case 'blog_entry':

                $template = 'blog_entry';

            break;

            case 'sortable':
                case 'sortable_with_reference':

                $template = 'sortable';

            break;

        }

        return $template;

    }

    /**
     * Create a Controller
     * @param string $name of Controller
     * @param string $template_name to use when creating
     */
    private function create_controller( $name, $template_name = null ){

        echo "Code_Alchemy: Adding new Controller `$name` using template `$template_name`\r\n";

        // No name?
        if ( ! strlen($name)>0 )

            // Explain!
            echo "Usage: xobjects create controller <name>\r\n\r\n";

        else {

            if ( $template_name )

                $source_name = $template_name.".php";

            else

                $source_name = ($name =='autogen')?'autogen.php':'template.php';

            $source_file = $this->root."templates/controllers/$source_name";

            $dest_file = $this->working_dir."/app/controllers/$name.php";

            if ( $this->copy_and_replace($source_file,$dest_file,array(
                '/_mynamespace_/'=>$this->guess_namespace(),
                '/__name__/' => $name
            )))

                echo "Bingo!  Your new Controller $name is ready to rock n' roll!\r\n\r\n";

        }

    }

    /**
     * @return bool true IFF overwrite of existing files is allowed
     */
    private function overwrite_allowed(){

        return !! ( isset( $this->switches['overwrite']) && $this->switches['overwrite']==='yes');

    }

    /**
     * @param string $name
     * @return string CamelCase for Name
     */
    private function camelcase_for( $name ){

        $result = '';

        foreach( explode('-',$name) as $part )

            $result .= ucfirst( $part )." ";

        return trim($result);
    }

    /**
     * Create a new BackboneJS View, with a template
     * @param string $name of new View
     */
    private function create_view( $name = null ){

        // By default, we win...
        $result = true;

        if ( $name ){

            // Type
            $type = isset( $this->switches['type'])? $this->switches['type']:'';

            // Get Model
            $model = isset( $this->switches['model'])? $this->switches['model']:'';

            // If Form, but no Model...
            if ( in_array($type,array('form','user_registration','datatable')) && ! $model ){

                $result = false;

                echo "Code_Alchemy: Error: When creating a View of this type, specify the Model using --model=<model_name>\r\n";

            } else {

                $dir = $this->working_dir."/js/views/";

                // copy over the view
                $dest = $dir . $name . ".js";

                $template_name = ( $type )? "_".$type ."_view_template.js": "_view_template.js";

                $should_create_view = true;

                // If View already exists
                if ( file_exists( $dest )){

                    // If we should not overwrite it
                    if ( ! $this->overwrite_allowed() ){

                        $should_create_view = false;

                        $result = false;

                        echo "Code_Alchemy: BackboneJS View file already exists.  Not overwriting.  To overwrite, use --overwrite=yes\r\n";

                    }
                }

                if ( $should_create_view ){

                    if ( $this->copy_and_replace($this->root."templates/js/views/". $template_name, $dest ,array(
                        '/{{name}}/'=>$name,
                        '/{{camelcase_name}}/'=>$this->camelcase_for( $name ),
                        '/{{model}}/'=>$model
                    )) )

                        echo "Code_Alchemy: Created new BackboneJS View $dest\r\n";

                    else {

                        // we lost
                        $result = false;

                        echo "\r\nError: Unable to create view from $template_name\r\n";

                    }

                }

                $template_name = ( $type )? $type ."-template.hbs": "template.hbs";

                $dir = $this->working_dir."/templates/";

                $dest = $dir.$name.".hbs";

                $should_create_view = true;

                if ( file_exists($dest) && ! $this->overwrite_allowed() ){

                    $should_create_view = false;

                    $result = false;

                    echo "Code_Alchemy: Handlebars Template file already exists.  Not overwriting.  To overwrite, use --overwrite=yes\r\n";

                }

                if ( $should_create_view ){

                    if ( @copy($this->root."templates/handlebars/".$template_name,$dest) ){

                        echo "Code_Alchemy: Created new Handlebars Template $dest\r\n";

                        if ( $this->should_git_add())

                            $this->git_add( $dest );
                    }

                    else

                    {

                        // we lost
                        $result = false;

                        echo "\r\nError: Unable to create Handlebars template from $template_name\r\n";
                    }

                }

                // When a Model is required
                if ( in_array($type,array('form','user_registration','datatable')))

                    // Ensure Model
                    $this->ensure_model( $model );


                // If this is a form...
                if ( $type =='form' && $result ){

                    // Add validation Library
                    $this->add_validation_library();

                    // Compose Form
                    $this->compose_form( $dest );


                }

                // For datatables
                if ( $type == 'datatable' )

                    $this->setup_datatables();


            }

            echo "Code_Alchemy: Done creating View.\r\n\r\n";

        } else echo
"Usage: xobjects create view <view_name> [arguments]\r\n
Acceptable arguments\r\n
\t--type={form|user_registration}\tSpecifies a specialized type of View, to fetch a specific template\r\n
\t--model=<model_name>\tSpecifies a model name, to bind with the View\r\n
\t--spec=<chunk>;<chunk>...;<chunk>\tSpecification for Forms, using the following chunk format\r\n
\t\tid:<field_id>,label:<field_label>,type=<field_type>\r\n
\tAcceptable Field Types:\r\n
\t\ttext\tFor simple text fields\r\n
\t\ttextarea\tFor text are input fields\r\n
\t\tfile\tFor file upload inpts\r\n
";


    }

    /**
     * Set up datatables
     */
    private function setup_datatables(){

        echo "Code_Alchemy: Setting up your application for use with datatables\r\n";

        $layout = $this->working_dir . "/app/views/layouts/bootstrap.php";

        $string = file_get_contents($layout);

        if ( ! preg_match('/dataTables/',$string)){

            echo "Code_Alchemy: It appears you don't have dataTables included in your layout\r\n";

            $file = fopen($layout,'r');

            $result = '';

            while ( $line = fgets($file) ){

                $result .= $line;

                if ( preg_match('/bootstrap\.min\.css/',$line) )

                    $result .= "\r\n".'<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">'."\r\n";

            }


            file_put_contents($layout,$result);

            echo "Code_Alchemy: We added the dataTables CSS via their CDN, to the layout\r\n";

            fclose($file);

            $file = fopen($layout,'r');

            $result = '';

            while ( $line = fgets($file) ){

                $result .= $line;

                if ( preg_match('/bootstrap\.min\.js/',$line) )

                    $result .= "\r\n<script type=\"text/javascript\" language=\"javascript\" src=\"//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js\"></script>\r\n";

            }


            file_put_contents($layout,$result);

            echo "Code_Alchemy: We added the dataTables CSS via their CDN, to the layout\r\n";


            fclose($file);

        } else

            echo "Code_Alchemy: It appears you already have dataTables included in your layout\r\n";



    }

    /**
     * Ensures that Table and files exist to support the Model
     * @param string $name of Model to ensure
     */
    private function ensure_model( $name ){

        echo "Code_Alchemy: Ensuring Model $name\r\n";

        // Get the database
        $db = $this->database;

        // If table doesn't exist
        if ( ! $db->has_table($name))

            // Add it
            $this->create_database_table($name,'base_table');

        // Create BackboneJS Model
        $this->create_backbonejs_model( $name );

        // Create BackboneJS Collection
        $this->create_backbonejs_collection($name );

        // Add the API, if missing
        $this->add_rest_api();

        // Add Server Model, if missing
        $this->create_server_model($name);

    }

    /**
     * Add the Validation Library to the App
     */
    private function add_validation_library(){

        $source = $this->root."/templates/js/xo-validate.js";

        $destination = $this->working_dir."/js/xo-validate.js";

        if ( ! file_exists( $destination ) && file_exists($source)){

            if ( copy($source,$destination)){

                echo "Code_Alchemy: Added missing client-side validation library\r\n";

                if ( $this->should_git_add() ) $this->git_add($destination);

            }

        }

    }

    /**
     * @return array|null Form Specification
     */
    private function get_form_spec(){

        $spec = null;

        $raw = isset( $this->switches['spec'])? $this->switches['spec']:null;

        if ( $raw ){

            echo "Code_Alchemy: Parsing Form Specification...\r\n";

            $spec = array();

            // Split into pieces
            $pieces = explode(';',$raw);

            echo "Code_Alchemy: Form Specification has ".count($pieces)." Pieces\r\n";


            // For each Piece
            foreach ( $pieces as $piece ){

                $chunk = array();

                $details = explode( ',',$piece);

                foreach ( $details as $detail ){

                    $parts = explode(':',$detail);

                    $chunk[ $parts[0] ] = $parts[1];

                }

                $spec[] = $chunk;

            }

        } else echo "Code_Alchemy: No Form Spec provided\r\n";

        return $spec;
    }

    /**
     * Compose the Form, from its constituents
     * @param string $template of Form to compose
     */
    private function compose_form( $template ){

        // Get Form Spec
        $spec = $this->get_form_spec();

        if ( $spec && file_exists( $template )){

            echo "Code_Alchemy: Specification is ".new \xo_array($spec)."\r\n";

            echo "Code_Alchemy: Getting ready to compose the Form\r\n";

            $file = fopen($template,'r');

            $contents = '';

            // For each line in the file
            while ( $line = fgets($file) ){

                $contents .= $line;

                if ( preg_match('/form\-group\-leader/',$line) ){

                    $contents .= $this->form_html_from_spec( $spec );

                }


            }

            // Close file
            fclose($file);

            // Now put contents
            file_put_contents($template,$contents);

        }

    }

    /**
     * Get the Form HTML, from the Specification
     * @param array $spec
     * @return string resulting HTML
     */
    private function form_html_from_spec( array $spec ){

        $html = '';

        // For each specification Rule
        foreach ( $spec as $rule ){

            echo "Code_Alchemy: Parsing Form Rule ".new \xo_array($rule)."\r\n";


            // Suss out type...
            $type = isset( $rule['type'])? $rule['type']:'text';

            // If type is Autogen
            if ( $type == 'autogen')

                // Make sure to add the Autogen Controller
                $this->create_controller('autogen');

            // If type is Typeahead
            if ( $type == 'typeahead')

                // Add Typeahead file
                $this->smart_copy_file(
                    $this->root."/templates/js/typeahead.js",
                    $this->working_dir."/js/typeahead.js"
                );

            // And Id
            $token = (string)new Random_Password(5);

            $id = isset( $rule['id'])? $rule['id']:( 'field_id_'. $token);

            // and Label
            $label = isset( $rule['label'])? $rule['label']:( 'Label '.$token);

            // Collection for certain cases
            $collection  = isset( $rule['collection'])? $rule['collection']:null;


            // Find the right fragment...
            $fragment = $this->root."/templates/fragments/form-".$type."-control.html";

            // If exists
            if ( file_exists($fragment)){

                // Append to HTML
                $raw_html = file_get_contents($fragment);

                // Put id
                $raw_html = preg_replace('/__id__/',$id,$raw_html);

                // And Label
                $raw_html = preg_replace('/__label__/',$label,$raw_html);

                // And Collection
                $raw_html = preg_replace('/__collection__/',$collection,$raw_html);


                $html .= $raw_html ."\r\n";

            }


        }

        return $html;

    }

    /**
     * Intelligently copy a file
     * @param string $source
     * @param string $destination
     * @return bool true if successful
     *
     */
    private function smart_copy_file( $source, $destination ){

        $result = true;

        if ( file_exists( $destination)){

            echo "Code_Alchemy: $destination: FIle already exists\r\n";

            if ( $this->overwrite_allowed() ){

                echo "Code_Alchemy: $destination: Overwriting existing file\r\n";

                $result = copy( $source, $destination );

            }

        } else {

            echo "Code_Alchemy: $destination: Copying over new file\r\n";

            $result = copy( $source, $destination );


        }

        if ( $this->should_git_add() ) $this->git_add($destination);

        return $result;

    }


    /**
     * Create Assets, like Models, Views and Controllers
     * @param $what
     * @param $root
     * @param $argv
     */
    public function create( $what, $root, $argv ){

        switch ( $what ){

            case 'service':

                $name = @$argv[3];

                $type = isset($this->switches['type'])?$this->switches['type']:'base';

                if ( $name ){

                    $builder = new Service_Builder($name,$type);

                    $builder->set_options($this->switches,get_called_class());

                    $builder->build($this->verbose);

                } else {

                    echo "Code_Alchemy: you must specify the name of the service\r\n\r\n";

                }

                if ( $this->verbose ) echo "\r\nDone building the service $name\r\n\r\n";

            break;

            case 'cron-job':

                $name = isset( $argv[3] ) ? $argv[3]: '';

                $this->create_cron_job( $name );

            break;


            case 'controller':

                $name = isset( $argv[3] ) ? $argv[3]: '';

                $this->create_controller( $name );

            break;

            case 'view':

                $this->create_view( isset( $argv[3] ) ? $argv[3]: null );


                break;

            case 'tables':

                $names = isset( $argv[3] ) ? $argv[3]: null;

                if ( $names )

                    foreach ( explode(',',$names )as $name )

                        $this->create_database_table($name);



                else echo "Usage: xobjects create tables <comma_list_of_table_names> [arguments]\r\n\r\nExample: create tables one,two,three,four\r\n";

                break;

            case 'table':

                $name = isset( $argv[3] ) ? $argv[3]: null;

                if ( $name ) {

                    $creator = new Database_Table_Creator($name,$this->switches['type']);

                    $creator->set_options($this->switches);

                    if ( $creator->create($this->verbose) ){

                        if ( $this->verbose)  echo "Table created\r\n\r\n";

                    } else {

                        if ( $this->verbose)  echo "failed to create Table\r\n\r\n";


                    }


                }

                else echo "Usage: xobjects create table <table_name> [arguments]\r\n
\tOptions: \r\n\r\n
\t--type=<table_type> (Possible values: base, base_with_reference,email_template,user_session,user)\r\n
\r\n";

            break;

            case 'models':

                $component = new Create_Models_From_JSON();

                $component->set_options($this->switches);

                $component->create(true);

                break;

            case 'model':

                $cwd = getcwd();

                $name = isset( $argv[3] ) ? $argv[3]: null;

                if ( $name ) {

                    $this->create_server_model($name);

                }

                else echo "Usage: codealchemy create model <model_name> [arguments]\r\n";

            break;



            default:

                echo
"Usage: codealchemy create <object>\r\n
Acceptable Objects:\r\n
\tservice <name> [--type=<type>]\tCreate a new managed service, including database table and server model\r\n
\ttable <name>\t\t\tCreate a Database table with given name\r\n
\ttables <comma,separated,names>\tCreate one or more database tables\r\n
\tview <name>\t\t\tCreate a new BackboneJS View, and Handlebars Template, with the given name\r\n
\tmodel <name>\t\t\tCreate a new server Model, BackboneJS Model, and BackboneJS Collection\r\n
\tcron-job <name>\t\t\tCreate a new Cron Job script\r\n
\tcontroller <name>\t\tCreate a new Server-routed Controller\r\n\r\n";

            break;
        }

    }

    /**
     * @param string $name for new service
     */
    public function create_cron_job( $name ){


        if ( ! $name )

            echo "Code_Alchemy: Error: You must specify a name for the Job\r\n\r\n";

        else {

            $tokens = explode('_',$name);

            array_walk( $tokens,function(&$element,$index){
                $element = ucfirst($element);
            });

            $class_name = implode('_',$tokens);


            // Create classfile
            $class_source = $this->root."/templates/classes/cronjob_class.php";

            $class_dest = $this->working_dir."/app/classes/$class_name.php";

            if ( $this->copy_and_replace($class_source,$class_dest,array(
                '/__mynamespace__/'=>$this->guess_namespace(),
                '/__classname__/'=>$class_name
            ))){

                echo "Code_Alchemy: Created new Cron Job Class '$class_name'\r\n";

            }

            // Create jobfile
            $job_source = $this->root."/templates/cronjob_file.php";

            // Make sure Jobs directory exists
            if ( ! file_exists("$this->working_dir/jobs") )

                mkdir("$this->working_dir/jobs");

            $job_dest = $this->working_dir."/jobs/$name.php";

            if ( $this->copy_and_replace($job_source,$job_dest,array(
                '/__mynamespace__/'=>$this->guess_namespace(),
                '/__classname__/'=>$class_name,
                '/__root__/'=>$this->root,
                '/__working_dir__/'=>$this->working_dir
            ))){

                echo "Code_Alchemy: Created new Cron Job File\r\n";

            }


        }

    }

    /**
     * Create a new Code_Alchemy Website or Application, with the given name
     * @param string $name of new application
     * @param string $root directory for install
     */
    public function webapp( $name, $root ){

        $builder = new Webapp_Builder(array(
            'root'=>$this->root,
            'user_options'=> $this->switches
        ));

        $builder->go();

    }

    /**
     * Parse any switches from command line
     * @param array $argv
     */
    private function parse_switches( array $argv = array() ){

        foreach( $argv as $arg ){

            if ( preg_match('/^--([a-z]+)=(.+)$/',$arg,$hits)){

                //echo "Code_Alchemy: Parsing Switch $arg\r\n";

                $this->switches[ $hits[1] ] = $hits[2];

                //echo "Code_Alchemy: Setting parameter '$hits[1]'  as '$hits[2]'\r\n";

            }


        }

    }

    /**
     * @param $source
     * @param $dest
     * @param $subs
     * @return bool true if successful
     */
    private function copy_and_replace($source, $dest, $subs){

        echo "Code_Alchemy: Source file is $source\r\n";

        echo "Code_Alchemy: Destination file is $dest\r\n";

        $result = true;

        if ( ! file_exists( $source )){

            $result = false;

            echo "Code_Alchemy: Fatal Error: $source: This file doesn't exist or cannot be opened\r\n\r\n";

        } else {

            $in = fopen ( $source, "r");


            if ( ! $in ) echo "$source: Could not open file for read!\r\n";

            if ( ! $in ) $result = false;

            $out = fopen( $dest, "w");

            if ( ! $out ) {
                $result = false;

            }

            // read in xml as a string
            while ( $data = fgets( $in ) ) {
                // replace app name
                foreach( $subs as $reg => $rep )
                    $data = preg_replace( $reg , $rep , $data );
                // save it
                if ( $out ) fputs( $out, $data );
            }
            if ( $in ) fclose( $in);
            if ( $out ) fclose( $out);

            // if we should add to Git
            if ( $this->should_git_add() )

                $this->git_add( $dest );

        }

        return $result;
    }

    /**
     * @return bool true if we should Git add
     */
    private function should_git_add(){

        return true;

    }

    /**
     * @param string $full_path of file to add
     */
    private function git_add( $full_path ){

        //shell_exec("git add $full_path");

    }


    // create a directory
    private function create_dir( $name){
        if ( file_exists( $name)){
            if ( ! is_dir( $name )){
                die("$name is not a directory!\r\n");
            } else {
            }
        } else {
            mkdir( $name);

        }

    }

    /**
     * @return string guessed namespace
     */
    private function guess_namespace(){

        $namespace = 'unknown';

        $cwd = getcwd();

        // method one: from X-Objects.XML
        if ( file_exists( "$cwd/app/xml/x-objects.xml")){

            // get it
            $xml = simplexml_load_file( "$cwd/app/xml/x-objects.xml");

            if ( isset( $xml->appname)) $namespace = (string) $xml->appname;

        } else {
            // method two: get from path

            $pieces = preg_match('/\//',$cwd) ? explode('/',$cwd): explode('\\',$cwd);

            $namespace = (string) array_pop( $pieces );

        }

        return $namespace;
    }

    /**
     * @param $theme_name
     * @return string Theme root for given theme name
     */
    private function theme_root_for( $theme_name ){

        $root = '';

        switch( $theme_name ){

            case 'flati': $root = '/themes/flati/'; break;
            case 'angle': $root = '/themes/angle/'; break;
            case 'alfie': $root = '/themes/alfie/html/full/blue/'; break;
        }

        return $root;
    }

    /**
     * Adds a page via a theme
     * @param null $page_name
     * @param null $template_name
     * @param $theme_name
     */
    public function add_theme_page( $page_name = null, $template_name = null, $theme_name ){

        $theme_root = $this->theme_root_for( $theme_name );

        $usage = "Usage: parnassus add $theme_name"."-page <page_name> <template_name> [options]\r\n\r\n";

        if ( ! $page_name || ! $template_name ) {

            echo $usage;

            return;

        }

        // Check if we have angle
        $angle_dir = $this->working_dir.$theme_root;

        if ( ! file_exists( $angle_dir) || ! is_dir($angle_dir)){

            echo "Code_Alchemy: Fatal: $angle_dir: No such directory.  You must have ".ucfirst($theme_name)." already installed.\r\n";

            return;

        }

        // Check if page exists
        $page = $angle_dir.$page_name.".php";

        if ( file_exists( $page ) && ! $this->overwrite_allowed() ){

            echo "Code_Alchemy: Warning: $page: Already exists.  To overwrite, use --overwrite=yes\r\n\r\n";

            return;

        }

        // First step: See if the Layout is alrady in Code_Alchemy
        $layout = "$this->root/templates/$theme_name/layouts/$template_name.php";

        echo "Code_Alchemy: $layout: Checking if file already exists.\r\n";

        if ( file_exists( $layout) ){

            echo "Code_Alchemy: Good News! $page_name is an existing layout, so we can reuse it.\r\n";

            if ( $this->copy_and_replace($layout,$page,array(
                '/__my_namespace__/'=> $this->guess_namespace()
            )))

                echo "Code_Alchemy: Oh Yeah! We've added the Layout to the app.\r\n\r\n";

        } else {

            // Check if source template exists
            $source_template = $angle_dir.$template_name.".html";

            if ( ! file_exists( $source_template) ){

                echo "Code_Alchemy: Fatal: $source_template: No such template exists.\r\n\r\n";

                return;

            }

            // Load source template into a String
            $source = file_get_contents( $source_template );

            // Adjust for theme root
            $source = preg_replace('/src="assets/','src="<?=$theme_root?>assets',$source);
            $source = preg_replace('/src="js/','src="<?=$theme_root?>js',$source);
            $source = preg_replace('/src="rs-plugin/','src="<?=$theme_root?>rs-plugin',$source);
            $source = preg_replace('/href="assets/','href="<?=$theme_root?>assets',$source);
            $source = preg_replace('/href="css/','href="<?=$theme_root?>css',$source);
            $source = preg_replace('/img src="img/','img src="<?=$theme_root?>img',$source);

            // Set control of Swatch
            $source = preg_replace('/swatch-red-white/','<?=$state->theme_swatch()?>',$source);

            // Get Angle templates directory
            $angle_templates_dir = $this->root."/templates/$theme_name/stubs/";

            // Get the pre-stub
            $pre_stub_file = $angle_templates_dir.'pre.php';

            // Load it
            $pre_stub = file_get_contents($pre_stub_file);

            // Get the post-stub
            $post_stub_file = $angle_templates_dir.'post.php';

            // Load it
            $post_stub = file_get_contents($post_stub_file);

            // Process it
            $post_stub = preg_replace( '/__name__/',$this->guess_namespace(),$post_stub);

            // Create new file
            $new_file = $pre_stub . $source . $post_stub;

            // Save it
            file_put_contents( $page,$new_file );

            echo "Code_Alchemy: That's it, I created a new ".ucfirst($theme_name)."Page based on the $template_name template.\r\n";

        }


    }

    /**
     * Refresh the application
     */
    public function refresh(){

        $specialist = new Application_Refresher();

        $specialist->set_options($this->switches);

        $specialist->perform_duties( true );

    }

    /**
     * Send more friendly error messages
     * @param $what
     * @param $how
     */
    public function __call( $what, $how ){

        echo "Code_Alchemy: Oops! $what: This method is not defined yet\r\n\r\n";

    }

}
