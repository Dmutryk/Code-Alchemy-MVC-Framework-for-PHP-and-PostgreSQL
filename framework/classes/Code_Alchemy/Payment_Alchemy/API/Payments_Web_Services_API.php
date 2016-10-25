<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/15/16
 * Time: 10:56 AM
 */

namespace Code_Alchemy\Payment_Alchemy\API;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Payment_Alchemy\Components\Payment;
use Code_Alchemy\Payment_Alchemy\Credentials\Service_Credentials;
use Code_Alchemy\Payment_Alchemy\Payment_Service;
use Code_Alchemy\Text_Operators\Token_Mask;

/**
 * Class Payments_Web_Services_API
 * @package Code_Alchemy\Payment_Alchemy\API
 *
 * A Web Services API for Payment Alchemy
 */
class Payments_Web_Services_API extends Array_Representable_Object{

    /**
     * @var Model client
     */
    private $client;

    /**
     * @var Payments_Client_Data
     */
    private $client_data;

    /**
     * Payments_Web_Services_API constructor.
     * @param array $client_data
     */
    public function __construct( array $client_data ) {

        // Stash client data
        $this->client_data = new Payments_Client_Data( $client_data );

        $node = (new REQUEST_URI())->part(2);

        if ( ! $node ){

            $this->result = 'error';

            $this->error = "Error 1: Please specify a node and optional edge.";

        }

        elseif ( ! method_exists($this,$node) ){

            $this->result = 'error';

            $this->error = "Error 2: $node: Unrecognized Node or method.";

        } elseif ( ! $this->is_authorized( $client_data) ){

            $this->result = 'error';

            $token = $client_data['_token'];

            $masked_token = (string) new Token_Mask($token);

            $this->error = "Error 3: Not authorized (Token $token)";

        }

            else

                $this->$node();

        // Sign the response
        $this->signature = $this->signature();

    }

    /**
     * @return bool true if authorized
     */
    private function is_authorized( array $client_data ){

        $client = (new Model('client'))

            ->find("access_token='".$client_data['_token']."',is_authorized='1'");

        // Cache for later reference
        $this->client = $client;

        return $client->exists;
    }

    /**
     * Obtener estado de pago
     */
    private function status(){

        $client_data = $this->client_data->as_array();

        // Make a payment
        $is_test = (bool)$this->client->get('is_test_mode');

        $client_id = $this->client->id();

        $credentials = $is_test ? null : new Service_Credentials(

            (new Model('payu_credential'))->find("client_id='$client_id'")->as_array());

        $payment_Service = (new Payment_Service());

        if ( $credentials ) $payment_Service->set_credentials( $credentials);

        $this->array_values = array_merge(

            $payment_Service

                ->payment_status( new Payment( $client_data ), $is_test )->as_array(),
            [

                'client_id' => $client_id
            ],
            array(

                'signature' => $this->signature()

            ));


    }


    /**
     * Pay
     */
    private function pay(){

        $client_data = $this->client_data->as_array();

        // Make a payment
        $is_test = (bool)$this->client->get('is_test_mode');

        $client_id = $this->client->id();



        $credentials = $is_test ? null : new Service_Credentials(

            (new Model('payu_credential'))->find("client_id='$client_id'")->as_array());

        $payment_Service = (new Payment_Service());

        if ( $credentials ) $payment_Service->set_credentials( $credentials);

        $this->array_values = array_merge(

            $payment_Service

            ->pay( new Payment( $client_data ),

                $is_test

            )->as_array(array(

                    'trace'

                )),
            [

                'client_id' => $client_id
            ],
            array(

            'signature' => $this->signature()

        ));

    }

    /**
     * @return array signature
     */
    private function signature(){

        return [

            'name' => "Code Alchemy Payment Alchemy Web Services API",

            'version' => 1.2,

            'copyright' => "(c) 2016 Alquemedia S.A.S. all rights reserved",

            'host' => $_SERVER['HTTP_HOST'],

            'client_id' => $this->client->id()

        ];
    }

}