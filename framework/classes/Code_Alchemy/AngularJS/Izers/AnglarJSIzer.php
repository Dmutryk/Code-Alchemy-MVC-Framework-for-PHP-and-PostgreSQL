<?php


namespace Code_Alchemy\AngularJS\Izers;


use Code_Alchemy\AngularJS\AngularJS_Cloudflare_Includes;
use Code_Alchemy\AngularJS\Helpers\Angular_App_Name;
use Code_Alchemy\AngularJS\Helpers\Angular_Controllers_Name;
use Code_Alchemy\AngularJS\Helpers\Angular_Factory_Name;
use Code_Alchemy\AngularJS\Helpers\AngularJS_Templates_Directory;
use Code_Alchemy\Applications\Toolboxes\Helpers\Text_Colorizer;
use Code_Alchemy\Components\Smart_Copy_Bundle;
use Code_Alchemy\Controllers\Application_Controllers;
use Code_Alchemy\Core\Managed_Component;
use Code_Alchemy\Filesystem\Text_File_As_Lines;
use Code_Alchemy\Helpers\Called_Class;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Themes\Bootstrap_jQuery_JavaScript_Includes;
use Code_Alchemy\Themes\Helpers\Theme_Component_Directory;
use Code_Alchemy\Themes\Helpers\Theme_Component_Full_Path;
use Code_Alchemy\Themes\Helpers\Theme_Root;
use Code_Alchemy\Themes\Layouts\Theme_Layouts;
use Code_Alchemy\Components\Statistics_Tracker;

class AnglarJSIzer extends Managed_Component{

    /**
     * @param array $options to set
     */
    public function __construct( array $options = array()){

        // set some targets
        $this->set_options($options);

        // Fetch some targets
        //$this->targets[] =

    }

    /**
     * Run the process
     */
    public function execute(){

        if ( $this->verbose() ) echo (string) new Called_Class($this).": Ready to rock and roll...\r\n\r\n";

        // Get a statistics manager
        $stats = new Statistics_Tracker();

        // Get the Head Component as Lines
        $homeComponent = new Text_File_As_Lines((string) new Theme_Component_Full_Path('head'));

        // Get a scout
        $scout = new Bootstrap_jQuery_JavaScript_Includes();

        // We should also remove any js specific to this app
        $removed_lines = array_merge( $scout->as_array(),array(
            (string) new Namespace_Guess()."\.js"
        ));

        // Remove Bootstrap and jQuery files
        $homeComponent->run_heuristic('remove   matching   lines',$removed_lines);

        // Get Angular Includes
        $includes = new AngularJS_Cloudflare_Includes();

        // Insert them after last match
        $homeComponent->run_heuristic(
            'insert after last match',
            array_merge(
                $includes->as_array(),
                array(

                    // Add some static includes for Angular App
                    '<script src="/js/ng-factory.js"></script>'."\r\n" ,
                    '<script src="/js/ng-controllers.js"></script>' . "\r\n",
                    '<script src="/js/ng-app.js"></script>'. "\r\n"

                )),array(
            'matches'=>array(
                "\.css\"",
                'rel\="stylesheet"'
            )
        ));


        if ( $homeComponent->commit_changes() ){

            if ( $this->verbose() ) echo "\tHead Component has been modified\r\n";

        } else {

            $warning = (string) new Text_Colorizer('Warning','light_red');

            if ( $this->verbose() ) echo "\t$warning: Failed to write changes to Head Component\r\n";

        }

        // Now analyze theme home controller
        $homeController = new Text_File_As_Lines( (string) new Theme_Root(getcwd(),(string)new Theme_Name_Guess())."/home.php");

        // Run some replacements
        $homeController->replace_text(array(
            '/\<html/'=>'<html ng-app="'.(string) new Angular_App_Name().'"'
        ));

        // Remove inline Javascript
        $homeController->run_heuristic('remove inline javascript');

        // Remove JS library references
        $homeController->run_heuristic('remove matching lines',array(
            '\<script src\=\".+\"\>\<\/script\>'
        ));

        if ( $homeController->commit_changes() ){

            if ( $this->verbose() ) echo "\tHome Controller has been modified\r\n";

        } else {

            $warning = (string) new Text_Colorizer('Warning','light_red');

            if ( $this->verbose() ) echo "\t$warning: Failed to write changes to Home Controller\r\n";

        }

        // Now copy over some files, replacing values at the same time
        $bundle = new Smart_Copy_Bundle(
            (string) new AngularJS_Templates_Directory('js'),
            getcwd()."/js",
            array(
                'ng-controllers.js','ng-app.js','ng-factory.js'
            ),
            array(
                '/__angular_appname__/'=>(string) new Angular_App_Name,
                '/__angular_app_controllers__/'=>(string) new Angular_Controllers_Name(),
                '/__angular_factory__/'=>(string) new Angular_Factory_Name(),
            )
        );



    }

}