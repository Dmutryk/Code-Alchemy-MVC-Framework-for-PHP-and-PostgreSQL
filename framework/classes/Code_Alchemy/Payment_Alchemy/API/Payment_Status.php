<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/6/16
 * Time: 2:40 PM
 */

namespace Code_Alchemy\Payment_Alchemy\API;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Payment_Alchemy\Components\Payment;

/**
 * Class Payment_Status
 * @package Code_Alchemy\Payment_Alchemy\API
 *
 *
 */
class Payment_Status extends Array_Representable_Object{

    /**
     * Payment_Status constructor.
     * @param Payment $payment
     */
    public function __construct( Payment $payment ) {

        $this->array_values = (new Payments_Web_Service_Client(

            array_merge([

                'action' => 'status'

            ],

                $payment->as_array())


        ))->as_array();


    }
}