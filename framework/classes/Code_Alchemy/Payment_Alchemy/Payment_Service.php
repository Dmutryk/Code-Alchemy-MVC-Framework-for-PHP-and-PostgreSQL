<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/4/15
 * Time: 6:32 PM
 */

namespace Code_Alchemy\Payment_Alchemy;


use Code_Alchemy\Payment_Alchemy\Components\Payment;
use Code_Alchemy\Payment_Alchemy\Components\Payment_Subscription;
use Code_Alchemy\Payment_Alchemy\Credentials\Service_Credentials;
use Code_Alchemy\Payment_Alchemy\Helpers\Payment_Broker_Name;

/**
 * Class Payment_Service
 * @package Code_Alchemy\Payment_Alchemy
 *
 * English:
 *
 * The Payment Service may be invoked by the Consumer (the end
 * application) to make online payments, using an installed
 * add-on service.
 *
 * español:
 *
 * El Servicio de Pagos puede ser utilizado por el Consumidor (
 * la aplicación actual) para realizar pagos, usando un add-on
 * instalado
 */
class Payment_Service extends Payment_Alchemy{

    /**
     * @var Service_Credentials
     */
    private $credentials = null;

    /**
     * Payment_Service constructor.
     * @param bool $enable_debugging
     */
    public function __construct( $enable_debugging = false ) {

        $this->_debug = $enable_debugging;

    }

    /**
     * @param Service_Credentials $credentials
     * @return Payment_Service
     */
    public function set_credentials( Service_Credentials $credentials ){

        $this->credentials = $credentials;

        return $this;

    }

    /**
     * @var bool true if test mode
     */
    private $_is_test = false;

    /**
     * @param Payment $payment
     * @param bool $is_test
     * @return Process_Result
     */
    public function pay( Payment &$payment, $is_test = false ){

        return $this->_payment_broker( $is_test )

            ->pay( $payment, $this->credentials );

    }

    /**
     * Cancel a pending payment
     * @param Payment $payment
     * @return Process_Result
     */
    public function cancel_payment( Payment &$payment ){

        return $this->_payment_broker()

            ->cancel_payment( $payment );
    }

    /**
     * Add a new Subscription
     * @param Payment_Subscription $subscription
     * @return Process_Result
     */
    public function add_subscription( Payment_Subscription &$subscription ){

        return $this->_payment_broker()

            ->add_subscription( $subscription );
    }

    /**
     * Refund a previously completed payment
     * @param Payment $payment
     * @return Process_Result
     */
    public function refund( Payment &$payment ){

        return $this->_payment_broker()

            ->refund( $payment );

    }

    /**
     * Cancel a Subscription
     * @param Payment_Subscription $subscription
     * @return Process_Result
     */
    public function cancel_subscription( Payment_Subscription &$subscription ){

        return $this->_payment_broker()

            ->cancel_subscription( $subscription );
    }

    /**
     * @param Payment $payment
     * @param bool $is_test
     * @return Process_Result
     */
    public function payment_status( Payment &$payment, $is_test = false ){

        return $this->_payment_broker( $is_test)

            ->payment_status( $payment, $this->credentials );
    }


    /**
     * @return Payment_Broker
     */
    private function _payment_broker( $is_test = false ){

        $payment_broker_classname = (string) new Payment_Broker_Name();

        return new $payment_broker_classname( $is_test, $this->_debug );

    }

}