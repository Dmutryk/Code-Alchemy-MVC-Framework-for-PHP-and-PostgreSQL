<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Integer_Value;
use Code_Alchemy\Multi_Language_Support\MLS_Manager;

/**
 * Class Controller_Key_Position
 * @package Code_Alchemy\Controllers\Helpers
 *
 * The Position in the URI to fetch Controller Key
 */
class Controller_Key_Position extends Integer_Value{

    public function __construct(){

        $PHP_SELF = $_SERVER['PHP_SELF'];

        //if ( $this->is_development() ) \FB::info(get_called_class().": PHP SELF is $PHP_SELF");
        $pieces = explode('/', $PHP_SELF);

        array_pop($pieces);

        $sub_directory = implode('/',$pieces);

        $controller_key_position = $sub_directory ? 2 : 1;

        // Adjust for MLS
        $MLS_Manager = (new MLS_Manager());

        if (

            $controller_key_position == 1

            && $MLS_Manager->is_enabled()

        ){

            $MLS_Manager->validate_uri();

            $controller_key_position = 2;

        }



        //if ( $this->is_development() ) \FB::info(get_called_class().": COntroller key position is $controller_key_position");

        $this->integer_value = $controller_key_position;

    }

}