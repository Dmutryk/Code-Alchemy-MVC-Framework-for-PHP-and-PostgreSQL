<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 3:01 PM
 */

namespace Code_Alchemy\Vendors\Twilio;

/**
 * Class Twilio_Say
 * @package Code_Alchemy\Vendors\Twilio
 *
 * Represents a "Say" component of a Twilio Response Object
 */
class Twilio_Say extends Twilio_Response_Object {

    public function __construct( $twilio_template, array $data = array()){

        parent::__construct(

        // Parse Twilio references
            (string) new Parsed_Twilio_String($twilio_template),

            $data);

        // Wrap with XML
        $this->string_representation = "<Say>$this->string_representation</Say>";


    }

}