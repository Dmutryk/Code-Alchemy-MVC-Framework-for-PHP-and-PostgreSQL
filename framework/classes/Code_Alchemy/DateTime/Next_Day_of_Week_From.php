<?php


namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Integer_Value;

class Next_Day_of_Week_From extends Integer_Value {

    public function __construct( array $days_of_week ){

        // Get today's day of week number
        $today = (int) date('N');

        // Start pointer one day ahead
        $pointer = $today == 7 ? 1: $today+1;

        // as long as we're not back to today
        while ( $pointer != $today ){

            // If pointer's day is set...
            if ( in_array( $pointer, $days_of_week) ){

                $this->integer_value = $pointer;

                break;
            }

            $pointer++;

            if ( $pointer > 7 ) $pointer = 1;


        }

    }

}