<?php


namespace Code_Alchemy\DateTime;


class Time_Difference {

    /**
     * @var array of datetimes
     */
    private $datetimes = array();

    /**
     * @param string $datetime1
     * @param string $datetime2
     */
    public function __construct( $datetime1 , $datetime2 ){

        // Save timestamps;
        $this->datetimes = array( $datetime1,$datetime2);

    }

    /**
     * @return int seconds difference
     */
    public function in_seconds(){


        return (int)abs(strtotime($this->datetimes[0])-strtotime($this->datetimes[1]));


    }

}