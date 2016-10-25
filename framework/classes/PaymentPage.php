<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 2/10/13
 * Time: 05:10 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\integrations\firstdata;
use xobjects;


class PaymentPage {

    //  Take from Payment Page ID in Payment Pages interface
    public $x_login = '';

    public $transaction_key = ""; // Take from Payment Pages configuration interface

    public $x_amount = '';

    public $x_currency_code = "USD"; // Needs to agree with the currency of the payment page

    public $x_fp_sequence = 0;

    public $x_fp_timestamp = 0;

    public $x_fp_hash = '';

    public function __construct( $options ){

        if ( ! is_array( $options ))
            throw new \IllegalArgumentException("You must pass an Array of parameters as the first argument when constructing a new ".get_class());

        if ( ! isset($options['x_login']))
            throw new \ObjectNotInitializedException("x_login: This option must be set in the Array passed to the constructor of ". get_class());

        if ( ! isset($options['x_amount']))
            throw new \ObjectNotInitializedException("x_amount: This option must be set in the Array passed to the constructor of ". get_class());

        if ( ! isset($options['transaction_key']))
            throw new \ObjectNotInitializedException("transaction_key: This option must be set in the Array passed to the constructor of ". get_class());

        xobjects\helpers\options::set( $this )->using( $options );

        srand(time()); // initialize random generator for x_fp_sequence

        $this->x_fp_sequence = rand(1000, 100000) + 123456;

        $this->x_fp_timestamp = time(); // needs to be in UTC. Make sure webserver produces UTC

        // The values that contribute to x_fp_hash
        $hmac_data = $this->x_login . "^" . $this->x_fp_sequence . "^" . $this->x_fp_timestamp . "^" . $this->x_amount . "^" . $this->x_currency_code;

        $this->x_fp_hash = hash_hmac('MD5', $hmac_data, $this->transaction_key);


    }

}