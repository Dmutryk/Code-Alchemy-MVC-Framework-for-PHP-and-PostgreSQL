<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/15/16
 * Time: 11:37 AM
 */

namespace Code_Alchemy\Payment_Alchemy\API;


use Code_Alchemy\APIs\Scaffolding\Web_Services_API_Client;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\cURL\cURL_Invocation;

/**
 * Class Payments_Web_Service_Client
 * @package Code_Alchemy\Payment_Alchemy\API
 *
 * A Client to interface with remote Payments Web Service
 */
class Payments_Web_Service_Client extends Web_Services_API_Client{

    /**
     * Payments_Web_Service_Client constructor.
     * @param array $request_data
     */
    public function __construct( array $request_data ) {

        parent::__construct('payments-service');

        $action = @$request_data['action'];

        if ( ! $action ){

            $this->result = 'error';

            $this->error = "Error 1: $action: No action specified.";


        } elseif ( ! method_exists($this,$action)){

            $this->result = 'error';

            $this->error = "Error 2: $action: Action not recognized.";

        } else $this->$action( $request_data );

    }

    /**
     * Post a Pay transaction to web service
     * @param array $client_data
     */
    private function pay( array  $client_data ){

        $client_data = $this->_package_data( $client_data);

        $body = (new cURL_Invocation(

            $this->_endpoint('pay'),

            $client_data,

            $this->_headers(),

            'POST',

            false

        ))->result()->as_array()['body'];

        $this->array_values = json_decode($body,true);

    }

    /**
     * Payment Status
     * @param array $client_data
     */
    private function status( array $client_data) {

        $client_data = $this->_package_data( $client_data);

        $body = (new cURL_Invocation(

            $this->_endpoint('status'),

            $client_data,

            $this->_headers(),

            'POST',

            false

        ))->result()->as_array()['body'];

        $this->array_values = json_decode($body,true);

    }

    /**
     * @param $node
     * @return string Endpoint, with node
     */
    private function _endpoint( $node ){

        $url = $this->endpoint ?

            $this->endpoint :"https://payments.alquimedia.co/service-api";

        return

            "$url/$node";

    }

    /**
     * @return array of headers to send with request
     */
    private function _headers(){

        return [];
    }


}