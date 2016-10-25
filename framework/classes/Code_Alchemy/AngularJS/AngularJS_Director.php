<?php


namespace Code_Alchemy\AngularJS;


use Code_Alchemy\AngularJS\Helpers\Angular_Names_Replacements;
use Code_Alchemy\AngularJS\Helpers\Controller_Name;
use Code_Alchemy\AngularJS\Helpers\Directive_Name;
use Code_Alchemy\Components\Smart_Copy_Bundle;
use Code_Alchemy\Directors\Process_Director;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\helpers\Smart_File_Copier;

class AngularJS_Director extends Process_Director {

    /**
     * @var array of args from command line
     */
    private $args = array();

    public function __construct( $command, $root, $args ){

        $this->command = $command;

        $this->args = $args;

        $this->usage_description = "AngularJS Director: Please indicate a command\r\n
            create:\tCreate AngularJS assets, like Directives, Controllers and Modules\r\n
";
  //      var_dump($args);

    }

    public function process_command(){

        if ( ! $this->command )

            $this->show_usage();

        else {

            if ( method_exists($this,$this->command)){

                $method = "$this->command";

                $this->$method();

            }


            else

                $this->show_usage();
        }

    }

    public function create(){

        $what = isset($this->args[3])? $this->args[3]:'';

        switch ( $what ){

            case 'directive':

                $name = isset( $this->args[4])? $this->args[4]:'';

                if ( ! $name )

                    echo "\tPlease specify a name for the Directive\r\n";

                else {

                    echo "$name: Creating this Directive\r\n";

                    $replacements = new Angular_Names_Replacements($name);

                    $directiveName = (string) new Directive_Name($name);

                    $controllerName = (string) new Controller_Name($name);

                    // Copy Directive
                    $copier = new Smart_File_Copier(
                        (string) new Code_Alchemy_Root_Path()."/templates/angularjs/js/directive.js",
                        getcwd()."/js/directives/$directiveName.js",
                        (new Angular_Names_Replacements($name))->as_array(),
                        false
                    );

                    if ( $copier->copy() )

                        echo "\tDirective has been created\r\n";

                    // Copy Directive
                    $copier = new Smart_File_Copier(
                        (string) new Code_Alchemy_Root_Path()."/templates/angularjs/js/controller.js",
                        getcwd()."/js/controllers/$controllerName.js",
                        (new Angular_Names_Replacements($name))->as_array(),
                        false
                    );

                    // Copy Controller
                    if ( $copier->copy() )

                        echo "\tController has been created\r\n";

                    // Copy Template
                    $copier = new Smart_File_Copier(
                        (string) new Code_Alchemy_Root_Path()."/templates/angularjs/handlebars/template.hbs",
                        getcwd()."/templates/$name.hbs",
                        (new Angular_Names_Replacements($name))->as_array(),
                        false
                    );

                    // Copy Controller
                    if ( $copier->copy() )

                        echo "\tHandlebars Template has been created\r\n";

                }
            break;
        }

    }

}