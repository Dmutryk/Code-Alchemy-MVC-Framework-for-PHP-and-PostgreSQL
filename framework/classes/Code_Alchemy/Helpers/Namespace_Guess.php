<?php


namespace Code_Alchemy\Helpers;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Configuration_File;

class Namespace_Guess extends Alchemist{

    /**
     * @var string Guess for Namespace
     */
    private $guess = '';

    /**
     * @param bool $avoid_recursion
     */
    public function __construct( $avoid_recursion = false ){

        $this->guess = $avoid_recursion ? '': (new Configuration_File())->find('namespace');

        global $webapp_location;

        if ( $webapp_location && ! $this->guess ) {

            $array = explode('/', $webapp_location);

            $this->guess = array_pop($array);

        }

        if ( ! $this->guess  )

            $this->guess = (string) Code_Alchemy_Framework::instance()->appname;

        if ( ! $this->guess )

            $this->guess = end(explode('/',getcwd()));

        //if ( $this->is_development() ) \FB::info("Namespace Guess is $this->guess");

    }

    public function __toString(){

        return $this->guess;
    }

}