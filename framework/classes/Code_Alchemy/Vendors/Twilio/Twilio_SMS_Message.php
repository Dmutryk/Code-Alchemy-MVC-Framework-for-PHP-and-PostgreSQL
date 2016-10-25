<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 5:02 PM
 */

namespace Code_Alchemy\Vendors\Twilio;

/**
 * Class Twilio_SMS_Message
 * @package Code_Alchemy\Vendors\Twilio
 *
 * A new SMS message as part of a Response
 */
class Twilio_SMS_Message extends Twilio_Response_Object{

    public function __construct( $twilio_template, array $data = array()){

        parent::__construct( $twilio_template, $data);

        $this->string_representation = "<Message>$this->string_representation</Message>";

    }

}