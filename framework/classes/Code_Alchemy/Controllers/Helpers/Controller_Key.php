<?php
namespace Code_Alchemy\Controllers\Helpers;

use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Multi_Language_Support\MLS_Manager;

class Controller_Key extends Stringable_Object {

    /**
     * @var string Controller Key
     */
    private $key = "";



    public function __construct(){

        $key = null;

        // get the parsed url
        $parsed_url = parse_url( "http://". $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);

        // now get the parts
        $url_parts = explode('/',$parsed_url['path']);

        $key_position = (new Controller_Key_Position())->int_value();

        // if available, set form part 1
        if ( isset($url_parts[ $key_position ])){

            $key = $url_parts[ $key_position ];

        } else {

            // get the URI
            $uri = new REQUEST_URI();

            // set controller from request
            $key = isset( $_REQUEST['key'] ) && $_REQUEST['key'] != '' ? $_REQUEST['key'] : '';

            // or from URI
            $key = (! $key )? $uri->part( $key_position ) : $key;

            $orig_uri = $key;
        }
        // or default to home
        $key = (! $key )? "home":$key;

        //if ( $this->is_development() ) \FB::info(get_called_class().": Controller Key is $key");

        $this->string_representation =  $this->key = $key;


    }


}
