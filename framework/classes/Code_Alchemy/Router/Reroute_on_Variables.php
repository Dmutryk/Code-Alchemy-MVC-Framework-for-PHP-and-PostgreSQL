<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/18/15
 * Time: 2:05 PM
 */

namespace Code_Alchemy\Router;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Reroute_on_Variables
 * @package Code_Alchemy\Router
 *
 * Reroute the page, upon the presence of certain variables
 */
class Reroute_on_Variables extends Alchemist{

    /**
     * @param array $variable_names
     * @param string $new_location
     */
    public function __construct( array $variable_names, $new_location ){

        $is_reroute = true;

        foreach ( $variable_names as $name )

            if ( ! isset( $_REQUEST[$name ])){

                $is_reroute = false;

                break;
            }

        if ( $is_reroute ) header("Location: $new_location");

    }

}