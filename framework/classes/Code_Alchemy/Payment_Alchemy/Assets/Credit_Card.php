<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 9/10/13
 * Time: 08:03 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Payment_Alchemy\Assets;


use Code_Alchemy\DateTime\Is_Date_Past;
use Code_Alchemy\Payment_Alchemy\Helpers\Payment_Method;
use Code_Alchemy\Payment_Alchemy\Validation\Credit_Card_Number;

class Credit_Card {

    const min_name_length = 5;

    /**
     * @var string the reason the card was invalid (if any)
     */
    public $invalid_reason = '';

    /**
     * @var string exposes which member is invalid
     */
    public $invalid_member = '';

    /**
     * Members for the card
     * @var string
     */
    private $payer = '',
            $credit_card = '',
            $expiration_date = '',
            $expiration_year = '',
            $expiration_month = '',
            $cvv = '',
            $zip_code = '';

    public function __construct( $members = array() ){

        foreach ( $members as $name=>$value)
            $this->$name = $value;

    }

    /**
     * @return bool true if card is valid
     */
    public function is_valid(){

        $result = true;

        /**
         * Must have valid CVV2
         */
        if ( ! preg_match('/[0-9]{3,4}/',$this->cvv)){

            $this->invalid_reason = 'CVV2: Must be present and 3-4 numeric digits';

            $this->invalid_member = 'cvv';

            $result = false;

        }

        /**
         * Minimum length for cardholder's name
         */
        if ( strlen($this->payer)< self::min_name_length) {

            $this->invalid_reason = 'Name must be at least '. self::min_name_length. ' characters';

            $this->invalid_member = 'payer';

            $result = false;
        }


        $number = new Credit_Card_Number($this->credit_card );

        // Insertion point for new validation algorithm
        if ( ! $number->is_valid()) {

            $this->invalid_reason = 'Not a valid card number';

            $this->invalid_member = 'credit_card';

            $result = false;

        }
        /**
         * invalid credit card number or format
         */
        $first_digit = substr($this->credit_card, 0, 1);

        if ( ! is_numeric($this->credit_card) || ! in_array( $first_digit,array('3','4','5','6'))

            || strlen($this->credit_card) < $this->min_card_length((int)$first_digit)){

            $this->invalid_reason = 'Card number too short, not valid, or not numeric';

            $this->invalid_member = 'credit_card';

            $result = false;

            return false;

        }

        /**
         * Invalid expiration date
         *
         *
         */
        if (
            ! preg_match('/(0[1-9]|1[0-2]){1}\-?[0-9]{2}/',$this->expiration_date)

            &&

            ! preg_match('/\d{4}\/\d{2}/',$this->expiration_date)
        )
        {
            $this->invalid_reason = 'Error 1: Invalid expiration date format';

            $this->invalid_member = 'expiration_date';

            $result = false;
        } else {

            $datestring = $this->expiration_year . "-" . $this->expiration_month . "-01 10:00:00";

            if ( (new Is_Date_Past($datestring))->bool_value()){

                $this->invalid_reason = "Error 2: Invalid expiration date (past date / $datestring)";
                $this->invalid_member = 'expiration_date';
                $result = false;

            }

        }



        /**
         * Invalid CVV2
         */
        if ( ! is_numeric($this->cvv) || (strlen($this->cvv)!= $this->cvv_length()))
        {
            $this->invalid_reason = 'Invalid CVV2';

            $this->invalid_member = 'cvv';

            $result = false;
        }


        return $result;

    }

    /**
     * CVV length
     * @return int
     */
    private function cvv_length(){

        $length = 3;

        switch ( (string) new Payment_Method($this->credit_card)){

            case 'AMEX':

                $length = 4;

            break;
        }

        return $length;
    }

    /**
     * @param $first_digit
     * @return int
     */
    private function min_card_length( $first_digit ){

        $min = 16;

        switch ( $first_digit){

            case 3:
                $min = 14;

                break;

        }

        return $min
            ;
    }

    /**
     * Is it a valid zip code?
     * @param $zip
     * @return bool
     */
    private function is_valid_zip( $zip ){
        return ! $zip || (bool)preg_match('/^\d{5}(-\d{4})?$/',$zip);
    }

    /**
     * @return string expiration date
     */
    public function expiration_date(){ return $this->expiration_date; }

    /**
     * @return string card number
     */
    public function card_number(){ return $this->card_number; }

    /**
     * @return string the cardholder name
     */
    public function cardholder_name(){ return $this->cardholder_name; }

    /**
     * @return string cvv code
     */
    public function cvv() { return $this->cvv; }

    /**
     * @return string zip code
     */
    public function zip_code(){ return $this->zip_code; }

}