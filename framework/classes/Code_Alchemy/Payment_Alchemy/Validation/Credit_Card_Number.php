<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/4/16
 * Time: 12:03 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Validation;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Credit_Card_Number
 * @package Code_Alchemy\Payment_Alchemy\Validation
 *
 * Is given credit card number valid
 */
class Credit_Card_Number extends Alchemist{

    /**
     * @var bool true if card number is valid
     */
    private $is_valid = false;

    /**
     * Credit_Card_Number constructor.
     * @param $credit_card_number
     */
    public function __construct( $credit_card_number ) {

        $odd = true;
        $sum = 0;

        foreach ( array_reverse(str_split($credit_card_number)) as $num) {

            $sum += array_sum( str_split(($odd = !$odd) ? $num*2 : $num) );

        }


        if (($sum % 10 == 0) && ($sum != 0)){

            $this->is_valid = true;

        }

    }

    /**
     * @return bool true if card is valid
     */
    public function is_valid(){ return $this->is_valid; }
}