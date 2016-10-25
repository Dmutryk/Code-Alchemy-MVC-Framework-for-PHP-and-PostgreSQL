<?php
/**
 * Represent the last day of a month
 */

namespace Code_Alchemy\helpers;


class last_day_of_month {

    /**
     * @var string last day of given month
     */
    private $last_day = '';

    /**
     * @param string $from date, if not this month
     */
    public function __construct( $from = null ){

        $this->last_day = date('Y-m-t',$from?strtotime($from):null);


    }

    /**
     * @return string representation of last day
     */
    public function __toString(){ return $this->last_day; }
}