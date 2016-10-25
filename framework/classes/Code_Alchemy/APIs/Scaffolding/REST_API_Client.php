<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/27/16
 * Time: 3:40 PM
 */

namespace Code_Alchemy\APIs\Scaffolding;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\HTTP\URI_Request_String;

/**
 * Class REST_API_Client
 * @package Code_Alchemy\APIs\Scaffolding
 *
 * Simple REST API Client
 */
class REST_API_Client extends Alchemist{

    /**
     * @var string APi Endpoint
     */
    private $endpoint = '';

    /**
     * @var array config
     */
    private $configuration = [];

    /**
     * @var array parameters to incldue in URI
     */
    private $included_uri_parameters = [];

    protected function __construct( $configuration_key, $uri_included_parameters = [] ) {

        $this->configuration = (array)(new Configuration_File())->find($configuration_key);

        $this->included_uri_parameters = $uri_included_parameters;

        $this->endpoint = @$this->configuration['endpoint'];

    }

    /**
     * @param $node
     * @param array $parameters
     * @return array
     */
    protected function _get_node( $node, array $parameters ){

        $parameters = $this->_add_required_parameters( $parameters );

        if ( ! $this->endpoint ) return [

            'result' => 'error',

            'error' => get_called_class().": No Endpoint was defined"

        ];

        set_error_handler(
            create_function(
                '$severity, $message, $file, $line',
                'throw new ErrorException($message, $severity, $severity, $file, $line);'
            ),E_WARNING | E_ERROR
        );

        try {

            $url = $this->endpoint . $node . new URI_Request_String($parameters);

            $data = file_get_contents($url);
        }
        catch (\Exception $e) {

            return [ 'result' => 'error', 'error' => $e->getMessage() ];

        }

        restore_error_handler();

        return json_decode($data);

    }

    /**
     * @param array $parms
     * @return array
     */
    private function _add_required_parameters( array $parms ){

        foreach ( $this->included_uri_parameters as $required ){

            $value = @$this->configuration[$required ];

            $parms[$required] = $value;
        }

        return $parms;

    }

}