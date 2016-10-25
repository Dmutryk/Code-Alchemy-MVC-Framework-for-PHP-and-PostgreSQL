<?php


namespace Code_Alchemy\formatters\currencies;


class Colombian_Pesos {

    /**
     * @var string Amount, formatted as COP
     */
    private $formatted_amount = '';

    /**
     * @param $amount
     * @param int $decimals
     */
    public function __construct( $amount, $decimals = 2, $hide_shortcode = false ){

        $number_format = number_format($amount, $decimals, ',', '.');

        $inverse_number_format = number_format(-$amount, $decimals, ',', '.');

        $this->formatted_amount =

            ($hide_shortcode?'':'COP').

            ( $amount < 0 ? "$($inverse_number_format)": '$'.$number_format)
                ;

    }

    /**
     * @return string representation of Currency
     */
    public function __toString(){

        return $this->formatted_amount;

    }
}