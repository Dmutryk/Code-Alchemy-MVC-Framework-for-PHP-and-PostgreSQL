<?php


namespace Code_Alchemy\Controllers\Actors;


use Code_Alchemy\Controllers\Helpers\Custom_Controller_Class;
use Code_Alchemy\Controllers\Helpers\Custom_Controller_Replacements;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Helpers\Namespace_Guess;

/**
 * Class Run_Custom_Controller
 * @package Code_Alchemy\Controllers\Actors
 *
 * Runs (and also Creates) a Custom Controller
 */
class Run_Custom_Controller extends Alchemist {

    public function __construct( array $route, array &$data, array $post_data, &$layout ){

        $conClass = (string) new Custom_Controller_Class($route['controller']);

        if ( class_exists( $conClass )){

            if ( is_subclass_of($conClass,'\\Code_Alchemy\\Controllers\\Custom_Controller')){

                $custom = new $conClass( $data, $post_data, $layout );

            } else {

                \FB::warn("$conClass: This class should be declared as a subclass of Custom_Controller");

            }

        } else {

            if ( $this->is_development() ){

                // Allows users to specify a type
                $type = isset($route['type']) ? $route['type']."_": '';

                // We can add it here
                if ( (new Smart_File_Copier(

                    (string) new Code_Alchemy_Root_Path()."/templates/classes/$type"."Custom_Controller.php",

                    Code_Alchemy_Framework::instance()->webroot()."/app/classes/".(string) new Namespace_Guess()."/Controllers/".$route['controller'].".php",

                    (new Custom_Controller_Replacements($route['controller']))->as_array(),

                    false

                ))->copy() )

                    \FB::info($route['controller'].": A new Custom Controller was deployed to your app");

            }

        }


    }

}