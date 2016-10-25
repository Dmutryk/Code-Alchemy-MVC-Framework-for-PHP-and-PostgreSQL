<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 4/10/13
 * Time: 08:56 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\beans;


class Transaction {

    private $transaction_type = '',
        $amount = 0.00,
        $cc_expiry = "",
        $cc_number = '',
        $cardholder_name = '',
        $reference_no = '',
        $customer_ref = '',
        $reference_3 = '',
        $cvd_presence_ind = 0,
        $cc_verification_str1 = '',
        $cc_verification_str2 = '',
        $zip_code = '',
        $gateway_id = '',
        $password = '';

    public function __construct( $parms ){

        foreach ( $parms as $name=>$value )
            $this->$name = $value;

    }

    public function sign( $gatewayid, $password ){

        $this->gateway_id = $gatewayid;

        $this->password = $password;

    }

    public function as_array(){

        $arr = array();

        foreach ( $this as $member => $value)
            $arr[$member] = $value;

        return $arr;
    }

}