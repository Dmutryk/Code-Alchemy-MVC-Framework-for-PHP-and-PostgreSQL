<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 7:49 PM
 */

namespace Code_Alchemy\Payment_Alchemy;


use Code_Alchemy\cURL\cURL_Call_Result;
use Code_Alchemy\cURL\cURL_Invocation;
use Code_Alchemy\Payment_Alchemy\Components\Payment;
use Code_Alchemy\Payment_Alchemy\Components\Payment_Configuration;
use Code_Alchemy\Payment_Alchemy\Components\Payment_Subscription;
use Code_Alchemy\Payment_Alchemy\Credentials\Service_Credentials;

/**
 * Class Payment_Broker
 * @package Code_Alchemy\Payment_Alchemy
 *
 * Payment Broker, extend this class to implement your broker
 */
abstract class Payment_Broker extends Payment_Alchemy{

    /**
     * Make a test payment, don't actually charge card
     * @param Payment $payment
     * @return Process_Result
     */
    abstract public function test( Payment $payment );

    /**
     * @param Payment $payment
     * @param Service_Credentials|null $credentials
     * @return mixed
     */
    abstract public function pay( Payment $payment, Service_Credentials $credentials = null );

    /**
     * Cancel a pending payment
     * @param Payment $payment
     * @return Process_Result
     */
    abstract public function cancel_payment( Payment $payment );

    /**
     * Add a new Subscription
     * @param Payment_Subscription $subscription
     * @return Process_Result
     */
    abstract public function add_subscription( Payment_Subscription $subscription );

    /**
     * Refund a previously completed payment
     * @param Payment $payment
     * @return Process_Result
     */
    abstract public function refund( Payment $payment );

    /**
     * Cancel a Subscription
     * @param Payment_Subscription $subscription
     * @return Process_Result
     */
    abstract public function cancel_subscription( Payment_Subscription $subscription );

    /**
     * Get the status of a pending payment
     * @param Payment $payment
     * @return Process_Result
     */
    abstract public function payment_status( Payment $payment, Service_Credentials $credentials = null );

    /**
     * Get the Payments configuration
     * @return Payment_Configuration
     */
    protected function _get_configuration(){

        return new Payment_Configuration();

    }

    /**
     * Set the Configuration
     * @param Payment_Configuration $configuration
     */
    protected function _set_configuration( Payment_Configuration $configuration ){

        $configuration->_persist();

    }

    /**
     * Create a Payment
     * @param array $values
     * @return Payment
     */
    protected function _create_payment ( array $values ){

        return new Payment( $values );

    }

    /**
     * @param Payment $payment
     * @return bool true if saved correctly
     */
    protected function _save_payment( Payment $payment ){

        return $payment->save();

    }

    /**
     * @param int $payment_id
     * @return Payment
     */
    protected function _fetch_payment( $payment_id ){

        return (new Payment())->fetch_by_id( $payment_id );

    }


    /**
     * Create a Payment Subscription
     * @param array $values
     * @return Payment_Subscription
     */
    protected function _create_subscription ( array $values ){

        return new Payment_Subscription( $values );

    }

    /**
     * @param Payment_Subscription $subscription
     * @return int Id of payment sub saved
     */
    protected function _save_subscription( Payment_Subscription $subscription ){

        return $subscription->save();

    }

    /**
     * @param int $subscription_id
     * @return Payment_Subscription
     */
    protected function _fetch_subscription( $subscription_id ){

        return (new Payment_Subscription())->fetch_by_id( $subscription_id );

    }

    /**
     * Call endpoint
     * @param string $node
     * @param array $http_headers
     * @param array $data
     * @param string $method
     * @return cURL_Call_Result
     */
    protected function __call_endpoint( $node, array $http_headers, array $data, $method = 'GET'){

        return (new cURL_Invocation(

            $this->_endpoint()."/$node",

            $data,

            $http_headers,

            $method ))

            ->result();

    }

    /**
     * @return string Endpoint for cURL
     */
    protected function _endpoint(){

        return (string) $this->_get_configuration()

            ->configuration()['endpoint'];
    }


}