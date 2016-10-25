<?php


namespace Code_Alchemy\Core;


class Application_Context {

    private static $xml;

    public function __construct(){

        // Load context
        if ( ! self::$xml )

            self::$xml = simplexml_load_file( (string) new Code_Alchemy_XML_File() );



    }

    /**
     * @param $key
     * @return \SimpleXMLElement[]
     */
    public function get( $key ){

        return self::$xml->$key;

    }
}