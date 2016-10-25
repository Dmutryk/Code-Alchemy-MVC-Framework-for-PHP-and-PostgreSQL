<?php
/**
 * User: "David Owen Greenberg" <code@x-objects.org>
 * Date: 16/10/12
 * Time: 09:01 AM
 */
class xo_resource_bundle extends magic_object {

    /**
     * @var bool true to send output to Firebug
     */
    private $firebug = false;

    /**
     * The minimum debugging level to show messages
     */
    const min_debug_level = 3;

    /**
     * @var array of resources
     */
    private $resources = array();

    /**
     * @param string $key to load
     */
    public function __construct($key){

        if ( $this->firebug ) FB::log(get_called_class(). ": loading resources for $key");

        // Set the language
        $language = (string) \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->configuration()->language;

        if ( ! $language ) $language = 'en';

        if ( $this->firebug ) FB::log(get_called_class(). ": language is $language");

        $this->key = $key;

        global $codealchemy_location,$webapp_location;

        global $container;

        $f = $codealchemy_location . "resources/".$language."/$key.ini";

        if ( $this->firebug ) FB::log(get_called_class(). ": file is $f");

        if ( file_exists($f)){

            $this->resources = parse_ini_file($f,true);

        } else {

            $f = $webapp_location . "/app/resources/$language/$key.ini";

            $this->f2 = $f;

            if ( file_exists($f)){

                $this->resources = parse_ini_file($f,true);

            }

        }

        if ( $this->firebug ) FB::log(get_called_class(). ": Done initializing resource bundle");

    }
    public function __get( $what ){
        $debug = false;
        global $container;
        $tag = new xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);
        switch($what){

            default:
                if ( isset($this->resources[$what]))
                    return $this->resources[$what];
                else {

                    return parent::__get($what);
                }
            break;
        }
    }

}
