<?php


namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Integer_Value;

/**
 * Class Next_Weekday_As_Timestamp
 * @package Code_Alchemy\DateTime
 *
 * Gets the next specified Weekday as a timestamp
 */
class Next_Weekday_As_Timestamp extends Integer_Value {

    /**
     * @param int $iso_weekday_number to walk towards
     * @param bool|false $go_backwards to go back, instead of forward
     */
    public function __construct( $iso_weekday_number, $go_backwards = false ){

        // Get today's number
        $today = (int) date('N');

        // start no difference
        $difference = 0;

        if ( ! $iso_weekday_number )

            return;

        // As long as we haven't found it
        while ( $today != $iso_weekday_number ){

            // Move today
            $go_backwards ? $today--: $today++;

            // Move difference
            $difference++;

            // Top end
            if ( $today > 7) $today = 1;

            // Bottom end
            if ( $today < 1 ) $today = 7;

            if ( $today == $iso_weekday_number)

                break;

        }

        $this->integer_value = strtotime(

            ($go_backwards? "-$difference days": "+$difference days")

        );

    }

}