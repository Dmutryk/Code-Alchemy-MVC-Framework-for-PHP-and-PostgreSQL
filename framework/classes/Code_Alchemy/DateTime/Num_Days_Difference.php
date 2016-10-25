<?php


namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Integer_Value;

/**
 * Class Num_Days_Difference
 * @package Code_Alchemy\DateTime
 *
 * Gets the number of days difference between two ISO
 * days of the week
 */
class Num_Days_Difference extends Integer_Value {

    /**
     * @param int $first_day to calculate
     * @param int $second_day to calcualte
     */
    public function __construct( $first_day, $second_day ){


        $difference = 0;

        $step = $first_day;

        if ( $second_day != $first_day ){

            while ( $step != $second_day ){

                $step++;

                if ( $step > 7 ) $step = 1;

                $difference++;

            }


        }

        //\FB::info(get_called_class().": getting difference between $first_day and $second_day which is $difference");

        $this->integer_value = $difference;

    }
}