<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/18/16
 * Time: 9:25 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Payment_Method
 * @package Code_Alchemy\Payment_Alchemy\Helpers
 *
 * Gets string representation of payment method
 */
class Payment_Method extends Stringable_Object{

    /**
     * Payment_Method constructor.
     * @param $credit_card_number
     */
    public function __construct( $credit_card_number ) {

        $method = '';

        switch ( (substr((string)$credit_card_number,0,1))){

            case '3': $method = $this->resolve_amex_diners((string)$credit_card_number); break;
            case '4': $method = 'VISA'; break;
            case '5': $method = 'MASTERCARD'; break;
            case '6': $method = 'DISCOVER'; break;
        }

        $this->string_representation = $method;

    }

    /**
     * @param $number
     * @return string
     */
    private function resolve_amex_diners( $number ){

        $first_two = substr($number,0,2);

        return ($first_two == '34' || $first_two == '37' ) ? 'AMEX': 'DINERS';

    }
}

