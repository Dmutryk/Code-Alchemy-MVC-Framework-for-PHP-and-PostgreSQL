<?php


namespace Code_Alchemy\Formatters;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class US_Telephone_Number
 * @package Code_Alchemy\Formatters
 *
 * Formats a Number per US Format
 */
class US_Telephone_Number extends Stringable_Object{

    public function __construct( $raw_format ){

        $this->string_representation = "(".substr( $raw_format, 0, 3).") "

            .substr($raw_format,3,3)."-".substr($raw_format,6);

    }
}