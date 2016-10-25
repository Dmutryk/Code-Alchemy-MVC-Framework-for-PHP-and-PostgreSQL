<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 3:10 PM
 */

namespace Code_Alchemy\Vendors\Twilio;


/**
 * Class Twilio_Gather
 * @package Code_Alchemy\Vendors\Twilio
 *
 * Creates a Twilio <Gather> response
 */
class Twilio_Gather extends Twilio_Response_Object {

    public function __construct( $twilio_template, $action_uri, $num_digits = 1, array $data = array() ){

        parent::__construct($twilio_template,$data);

        $this->string_representation =

            "<Gather numDigits=\"$num_digits\" action=\"$action_uri\" method=\"POST\"><Say>$this->string_representation</Say></Gather>";


    }

}