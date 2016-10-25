<?php


namespace Code_Alchemy\Builders;


use Code_Alchemy\Applications\Dynamic_Console;
use Code_Alchemy\Applications\Toolboxes\Command_Line;
use Code_Alchemy\Applications\Toolboxes\Helpers\Text_Colorizer;
use Code_Alchemy\Applications\Web_Director;
use Code_Alchemy\Builders\Helpers\Login_System_Attacher;
use Code_Alchemy\Controllers\Controller_Configuration_File;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Core\Managed_Component;
use Code_Alchemy\Directors\Theme_Director;
use Code_Alchemy\Filesystem\Directory_Creator;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Templates\HTML5\Layout_Post_Stub;
use Code_Alchemy\Templates\HTML5\Layout_Pre_Stub;
use Code_Alchemy\Templates\Layouts\AngularJS_Layout;
use Code_Alchemy\apis\directory_api;
use Code_Alchemy\Text_Operators\Text_Template;

/**
 * Class Webapp_Builder
 * @package Code_Alchemy\Builders
 *
 * Builds new Websites and Web Applications, by imposing the Code
 * Alchemy framework on a known directory, and also by programming
 * any present HTML5 theme
 */
class Webapp_Builder extends Managed_Component{

    /**
     * @var string Version of Builder
     */
    private $version = '1.4.4';

    /**
     * @var bool true to send verbose output
     */
    private $verbose = true;

    /**
     * @var string Working dir to install App
     */
    private $working_dir = '';

    /**
     * @var string root for Code_Alchemy
     */
    private $root = '';

    /**
     * Directories to create
     */
    private $directories = array(
        'templates',
        "css","js","images" ,'img',"app",
        "app/classes",
        "app/controllers",

        // Directories for Models, Controllers, etc
        "app/classes/{{namespace}}",
        "app/classes/{{namespace}}/Components",
        "app/classes/{{namespace}}/Controllers",
        "app/classes/{{namespace}}/Models",
        "app/classes/{{namespace}}/Models/As_Array_Pre_Filters",
        "app/classes/{{namespace}}/Models/Custom_Methods",
        "app/classes/{{namespace}}/Models/Triggers",
        "app/classes/{{namespace}}/Models/Triggers/Before_Insert",
        "app/classes/{{namespace}}/Models/Triggers/Before_Update",
        "app/classes/{{namespace}}/Models/Triggers/Before_Delete",
        "app/classes/{{namespace}}/Models/Triggers/After_Insert",
        "app/classes/{{namespace}}/Models/Triggers/After_Update",
        "app/classes/{{namespace}}/Models/Triggers/After_Delete",

        "app/classes/{{namespace}}/Models/Error_Filters",

        "app/views",'app/views/components',
        "app/views/layouts",
        'app/config'
    );

    /**
     * @param array $options to set when starting up
     */
    public function __construct( $options = array() ){

        foreach ( $options as $name =>$value )

            if ( property_exists($this,$name) )

                $this->$name = $value;


    }

    /**
     * Run the Builder
     */
    public function go(){

        if ( $this->verbose ) {

            echo "Code_Alchemy Webapp Builder v$this->version\r\n";

        }

        // Step 1: Get the working directory
        $this->working_dir = getcwd();

        if ( $this->verbose ) {
            echo "\tRoot is ".(string) new Text_Colorizer($this->root,'NOTE')."\r\n";
            echo "\tWorking directory is ".(string) new Text_Colorizer($this->working_dir,'NOTE')."\r\n\r\n";

        }

        // set namespace
        $tokens = explode('/',$this->working_dir);

        $name = $tokens[(count($tokens)-1)];

        $namespace = $name;

        $theme_name = $this->theme_name();

        // if allowed
        if ( $this->allowed() ){

            if ( ! $theme_name ){

                echo "\tNo HTML5 Theme is present so building with defaults\r\n";

                if ( isset( $this->user_options['angularjs'])){

                    echo "\tBuilding new ".(string) new Text_Colorizer('AngularJS','SUCCESS') ." Application\r\n";

                    $layout = (string) new AngularJS_Layout();

                } else {

                    $layout = file_get_contents("$this->root/templates/views/layouts/bootstrap.php");

                }

                $pre_stub = new Layout_Pre_Stub();

                $post = new Layout_Post_Stub();

                file_put_contents("$this->working_dir/app/views/layouts/home.php",$pre_stub.$layout.$post);

            }

            // 1. Set up directories
            foreach ( $this->directories as $dir ){

                // Substitute values
                $dir = (string) new Text_Template($dir,array(
                    'namespace'=>$namespace
                ));

                if ( ! file_exists( "$this->working_dir/$dir") )

                    if ( mkdir( "$this->working_dir/$dir") ){

                        if ( $this->verbose) echo "\t$dir: Directory created\r\n";

                    }



            }

            // components for theme
            if ( $this->theme_name() && ! file_exists( "$this->working_dir/app/views/components/".$this->theme_name() )){

                $cr = new Directory_Creator("$this->working_dir/app/views/components/".$this->theme_name());

                $cr->create(true );

            }

            // Create configuration files
            $config = new Configuration_File();

            // 2. Add an Index File
            $this->smart_copy_file(
                "$this->root/templates/index.newwebapp.php",
                "$this->working_dir/index.php",
                array(
                    '/__root__/'=>$this->root,
                    '/__working_dir__/'=>$this->working_dir
                ),false);


            // Force creation of default Controllers' Config
            $controllers = new Controller_Configuration_File();

            // Copy htaccess file
            $this->smart_copy_file("$this->root/templates/misc/htaccess.txt",
                "$this->working_dir/.htaccess",array(),true);

            // Now add some Components
            $builders = array();

            // Foreach service specified in config
            foreach ( (new Webapp_Builder_Configuration())->find('services') as $service_name )

                // Build it!
                $builders[] = new Service_Builder($service_name,$service_name);

            // For each builder
            foreach ( $builders as $builder ){

                $builder->set_options($this->user_options);

                $builder->build( $this->verbose );

            }



            // if we have a theme
            if ( $theme_name ){

                // Get the theme director
                $themeDirector = new Theme_Director( $theme_name,array(
                    'root'=>$this->root,
                    'is_overwrite'=>!! isset( $this->user_options['overwrite'])
                ) );

                // Analyze and Bind the Theme
                $themeDirector->analyze_and_bind( $this->verbose );

            }

            // Add some applications

            // Add Web Director
            $webDir = new Web_Director();

            // Pass through user options
            $webDir->set_options($this->user_options);

            // Deploy it
            $webDir->deploy($this->verbose);

            // Dynamic Console
            $dynCons = new Dynamic_Console();

            $dynCons->set_options($this->user_options);

            $dynCons->deploy(false);

            // Add Code_Alchemy Configuration service
            $service = new Service_Builder('parnassus_configuration','configuration');

            $service->set_options($this->user_options);

            $service->build($this->verbose);


            // Attach login system
            (new Login_System_Attacher())->attach();

            // Run Refresh to finalize build
            (new Command_Line(array()))->refresh();

            // Remove Code_Alchemy OK file
            unlink( getcwd()."/code-alchemy-ok");


        } else {

            $warning = (string ) new Text_Colorizer("Warning",'light_red');

            echo "\t$warning: Not allowed to build an application here.  Execute 'touch code-alchemy-ok' in the directory where you want to install.\r\n\r\n" ;

        }


        if ( $this->verbose ) echo "\r\nDone building the application\r\n\r\n";

    }

    /**
     * @return string theme name
     */
    private function theme_name(){

        static $name = '';

        if ( ! $name ){

            $dir = new directory_api("$this->working_dir/themes");

            $directory_listing = $dir->directory_listing();

            if ( count($directory_listing)){

                $name = array_shift($directory_listing);
            }


            if ( $this->verbose ) echo "\tDetected Theme: $name\r\n";


        }

        return $name;
    }

    /**
     * @return bool true if allowed to build
     */
    private function allowed(){

        return !! file_exists( "$this->working_dir/code-alchemy-ok" );

    }

    /**
     * Smart Copy a File
     * @param string $source to copy
     * @param string $destination
     * @param array $replacements in file
     * @param bool $add_to_git
     */
    private function smart_copy_file( $source, $destination, $replacements = array(), $add_to_git = true ){

        // Smart Copy the File
        $file = new Smart_File_Copier($source,$destination,
            $replacements,
            !! (@$this->user_options['overwrite'] == 'yes'));

        if ( $file->copy() ){

            if ( $add_to_git ) shell_exec("git add $destination >/dev/null 2>/dev/null");

        } else {

            if ( $this->verbose ) echo "Home controller: $file->error\r\n";

        }


    }

}