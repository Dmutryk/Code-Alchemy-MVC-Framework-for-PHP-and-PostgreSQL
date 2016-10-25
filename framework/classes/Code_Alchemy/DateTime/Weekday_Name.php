<?php


namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Weekday_Name
 * @package Code_Alchemy\DateTime
 *
 * Represent a Weekday by name, given its ISO Number
 */
class Weekday_Name extends Stringable_Object {

    /**
     * @param int $weekday_iso_number
     */
    public function __construct( $weekday_iso_number ){

        $this->string_representation = date('l',(new Next_Weekday_As_Timestamp($weekday_iso_number))->int_value());

    }

}