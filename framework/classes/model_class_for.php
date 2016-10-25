<?php
/**
 * Guess the Model Class (including Namespace) for a given name
 * User: davidg
 * Date: 1/3/15
 * Time: 8:19 AM
 */

namespace Code_Alchemy\helpers;


class model_class_for {

    /**
     * @var string name to parse
     */
    private $name = '';

    /**
     * @var string The guessed Model Class
     */
    private $model_class = '';

    /**
     * @var bool send output to firebug
     */
    private $firebug = false;

    /**
     * @param string $name to guess Model Class from
     */
    public function __construct( $name ){

        if ( $this->firebug ) \FB::log("Finding Model Class for $name");

        $this->name = $name;

        $guess = "\\". \x_objects::instance()->configuration()->appname. "\\models\\".$name;

        if ( class_exists( $guess )) $this->model_class = $guess;

        else {

            $guess = \x_objects::instance()->configuration()->appname. "\\".$name;

            if ( $this->firebug ) \FB::log("Guess is $guess");

            if ( class_exists( $guess )) $this->model_class = $guess;

            else {

                $guess = "\\".$name;

                if ( $this->firebug ) \FB::log("Guess is $guess");

                if ( class_exists( $guess )) $this->model_class = $guess;

            }

        }

    }

    /**
     * @return string guessed Model Class Name
     */
    public function __toString(){

        if ( $this->firebug ) \FB::log("Model class for $this->name is $this->model_class");

        return $this->model_class;

    }
}