<?php


namespace Code_Alchemy\Helpers;


class Code_Alchemy_Root_Path {

    /**
     * @var string Root Path for Code_Alchemy
     */
    private static $path = '';

    /**
     *
     */
    public function __construct(){

        global $codealchemy_location;

        if ( ! self::$path ) self::$path = $codealchemy_location;

    }

    /**
     * @return string Code_Alchemy Root Path
     */
    public function __toString(){ return self::$path; }

}