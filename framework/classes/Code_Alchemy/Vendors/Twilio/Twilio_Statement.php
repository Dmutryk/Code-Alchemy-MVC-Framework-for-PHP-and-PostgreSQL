<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 2:27 PM
 */

namespace Code_Alchemy\Vendors\Twilio;


use Code_Alchemy\Text_Operators\Text_Template;

/**
 * Class Twilio_Statement
 * @package Code_Alchemy\Vendors\Twilio
 */
class Twilio_Statement extends Text_Template {

    public function __construct( $twilio_template, array $data = array() ){

        parent::__construct(

            // Parse Twilio references
            (string) new Parsed_Twilio_String($twilio_template),

            $data);

        // Wrap with XML
        $this->string_representation = "<Response><Say>$this->string_representation</Say></Response>";

    }

}