<?php
/**
 * Represent the first day of a month
 */

namespace Code_Alchemy\helpers;


class first_day_of_month {

    /**
     * @var string first day of given month
     */
    private $first_day = '';

    /**
     * @param string $from date
     */
    public function __construct( $from = null){

        $this->first_day = date('Y-m-01',$from?strtotime($from):null);


    }

    /**
     * @return string representation of first day
     */
    public function __toString(){ return $this->first_day; }
}