<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/30/15
 * Time: 8:18 PM
 */

namespace Code_Alchemy\HTTP;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class URI_Request_String
 * @package Code_Alchemy\HTTP
 *
 * Constructs a URI request string (query string) from an array of parameters
 */
class URI_Request_String extends Stringable_Object{

    public function __construct( array $parameters ){

        $qs = '';

        foreach ( $parameters as $name => $value ){

            $value_component = $value===null? "$name":"$name=$value";

            $qs .= $qs? "&$value_component":"?$value_component";


        }

        $this->string_representation = $qs;
    }

}