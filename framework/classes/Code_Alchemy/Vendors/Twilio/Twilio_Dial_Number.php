<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 3:20 PM
 */

namespace Code_Alchemy\Vendors\Twilio;


/**
 * Class Twilio_Dial_Number
 * @package Code_Alchemy\Vendors\Twilio
 *
 * Dial a specific number, with a failed dial message
 */
class Twilio_Dial_Number extends Twilio_Response_Object{

    /**
     * Dial a number
     * @param string $number_to_dial
     * @param array $twilio_template
     * @param array $data
     */
    public function __construct( $number_to_dial, $twilio_template, array $data = array()){

        parent::__construct( $twilio_template,$data);

        $this->string_representation = "<Dial>$number_to_dial</Dial><Say>$this->string_representation</Say>";

    }

}