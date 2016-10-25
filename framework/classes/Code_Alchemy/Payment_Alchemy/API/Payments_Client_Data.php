<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 3/9/16
 * Time: 9:05 PM
 */

namespace Code_Alchemy\Payment_Alchemy\API;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Payments_Client_Data
 * @package Code_Alchemy\Payment_Alchemy\API
 *
 * Represent Payments Client Data
 */
class Payments_Client_Data extends Array_Representable_Object{

    /**
     * Payments_Client_Data constructor.
     * @param array $client_data
     */
    public function __construct( array $client_data ) {

        // Set IP address if not set
        if ( ! isset($client_data['ip_address']))

            $client_data['ip_address'] = $_SERVER['REMOTE_ADDR'];

        $this->array_values = $client_data;

    }

}