<?php


namespace Code_Alchemy\Helpers;


use Code_Alchemy\apis\directory_api;

class Theme_Name_Guess {

    /**
     * @var string Guess for Namespace
     */
    private $guess = '';

    /**
     * Guess Theme Name
     */
    public function __construct(){

        $dir = getcwd()."/themes/";

        $api = new directory_api($dir);

        $listing = $api->directory_listing();

        $this->guess = @array_shift( $listing );

        if ( ! $this->guess ) $this->guess = '';

    }

    public function __toString(){

        return $this->guess;
    }

}