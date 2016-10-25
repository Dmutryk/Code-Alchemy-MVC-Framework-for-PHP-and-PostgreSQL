<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 2:39 PM
 */

namespace Code_Alchemy\Vendors\Twilio;


use Code_Alchemy\Core\Stringable_Object;

class Parsed_Twilio_String extends Stringable_Object {

    /**
     * @param string $original_string
     */
    public function __construct( $original_string ){

        // Perform replacements based on a preceeding at
        if ( preg_match_all("/@Twilio\.([a-z|A-Z|0-9|_]+)/",$original_string,$hits))

            foreach ($hits[1] as $member){

                $original_string = preg_replace("/@Twilio.$member/",

                    (string) new Twilio_Variable("@Twilio.$member"),

                    $original_string

                );
            }

        $this->string_representation = $original_string;


    }

}