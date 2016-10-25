<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/17/16
 * Time: 9:50 AM
 */

namespace Code_Alchemy\APIs\Scaffolding;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\cURL\cURL_Invocation;

/**
 * Class Web_Services_API_Client
 * @package Code_Alchemy\APIs\Scaffolding
 *
 * Generic Web Services APi Client
 */
abstract class Web_Services_API_Client extends Array_Representable_Object{

    /**
     * @var string
     */
    protected $service_name = '';

    /**
     * @var string Endpoint
     */
    protected $endpoint = '';

    /**
     * @var array configuration
     */
    protected $configuration = array();

    /**
     * Web_Services_API_Client constructor.
     * @param $configuration_key
     */
    public function __construct( $configuration_key ) {

        $this->configuration = (new Configuration_File())

            ->find( $configuration_key );


        $this->endpoint = @$this->configuration['endpoint'];

    }

    /**
     * @param $node
     * @return string
     */
    private function _endpoint( $node ){

        return $this->endpoint."/$node";

    }

    /**
     * @return array
     */
    private function _headers(){ return []; }

    protected function _curl_invocation( $node, $client_data, $is_debug = false, $bodyTag  = 'body' ){

        // Package data
        $client_data = $this->_package_data( $client_data);

        $as_array = (new cURL_Invocation(

            $this->_endpoint($node),

            $client_data,

            $this->_headers(),

            'POST',

            $is_debug

        ))->result()->as_array();

        $body = $bodyTag ? $as_array[$bodyTag]: $as_array;

        \FB::info($body);
        return json_decode($body,true);

    }

    /**
     * Package data for remote calls
     * @param array $data
     * @return array
     */
    protected function _package_data( array $data ){

        // No token?
        if ( ! isset($data['_token']))

            $data['_token'] = @$this->configuration['token'];

        return $data;

    }




}