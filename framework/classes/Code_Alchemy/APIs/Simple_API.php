<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/30/15
 * Time: 8:03 PM
 */

namespace Code_Alchemy\APIs;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\HTTP\URI_Request_String;

/**
 * Class Simple_API
 * @package Code_Alchemy\APIs
 *
 * Represents a Simple API, usually invoked via GET or POST
 * to an endpoint, and with a few simple parameters
 */
class Simple_API extends Array_Representable_Object{

    /**
     * @var string Endpoint to hit when invoking
     */
    protected $endpoint = '';

    /**
     * @var array of parameters to send to the endpoint
     */
    protected $parameters = array();

    /**
     * @return $this for chaining
     */
    public function invoke(){

        if ( ! strlen( $this->endpoint ) )

            \FB::error(get_called_class().": Endpoint has not been defined");

        elseif ( ! count($this->parameters ) )

            \FB::error(get_called_class().": No URL parameters have been specified");

        else

            $this->array_values = json_decode(@file_get_contents($this->endpoint.new URI_Request_String($this->parameters)),true);

        return $this;

    }

}