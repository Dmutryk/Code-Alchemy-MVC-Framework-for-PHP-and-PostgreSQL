<?php
/**
 * Guess the Model Class (including Namespace) for a given name
 * User: davidg
 * Date: 1/3/15
 * Time: 8:19 AM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Stringable_Object;

class Model_Class_For extends Stringable_Object {

    /**
     * @var string name to parse
     */
    private $name = '';

    /**
     * @var string The guessed Model Class
     */
    private $model_class = '\\Code_Alchemy\\Models\\Dynamic_Model';

    /**
     * @var bool send output to firebug
     */
    private $firebug = false;

    /**
     * @param string $name to guess Model Class from
     */
    public function __construct( $name ){

        global $autoload_bypass_exception;

        $autoload_bypass_exception = true;

        if ( $this->firebug ) \FB::log("Finding Model Class for $name");

        $this->name = $name;

        $guess = "\\". $name;

        if ( $this->firebug ) \FB::log("Guess is $guess");

        if ( class_exists( $guess )) {

            if ( $this->firebug) \FB::info("$guess: class exists");

            $this->model_class = $guess;

            $this->string_representation = $this->model_class;


            return;
        }


        $guess = "\\". Code_Alchemy_Framework::instance()->configuration()->appname. "\\models\\".$name;

        if ( $this->firebug ) \FB::log("Guess is $guess");

        if ( class_exists( $guess )) $this->model_class = $guess;

        else {


            $guess = "\\". Code_Alchemy_Framework::instance()->configuration()->appname. "\\models\\".(string) new CamelCase_Name($name,'_');

            if ( $this->firebug ) \FB::log("Guess is $guess");

            if ( class_exists( $guess )) $this->model_class = $guess;

            else {


                $guess = Code_Alchemy_Framework::instance()->configuration()->appname. "\\".$name;

                if ( $this->firebug ) \FB::log("Guess is $guess");

                if ( class_exists( $guess )) $this->model_class = $guess;

                else {

                    $guess = "\\".$name;

                    if ( $this->firebug ) \FB::log("Guess is $guess");

                    if ( class_exists( $guess )) $this->model_class = $guess;

                }


            }

        }

        if ( $this->firebug ) \FB::info("Found!  Model class is $this->model_class");

        $this->string_representation = $this->model_class;

        $autoload_bypass_exception = false;


    }

}