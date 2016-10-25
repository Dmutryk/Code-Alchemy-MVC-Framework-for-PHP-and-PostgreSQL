<?php


namespace Code_Alchemy\Themes\Layouts;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Themes\Helpers\Theme_Root;
use Code_Alchemy\apis\directory_api;

class Theme_Layouts extends Array_Representable_Object {

    public function __construct(){

        $layouts = array();

        $dir = new directory_api( (string) new Theme_Root(getcwd(),(string) new Theme_Name_Guess())  );

        foreach( $dir->directory_listing(true) as $file )

            if ( preg_match("/\.php$/",$file))

                $layouts[] = $file;

        $this->array_values = $layouts;

    }

    /**
     * @return Theme_Layouts
     */
    public static function create(){

        return new self;

    }
}