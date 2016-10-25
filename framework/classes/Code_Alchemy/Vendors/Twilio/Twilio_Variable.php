<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 2:42 PM
 */

namespace Code_Alchemy\Vendors\Twilio;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Twilio_Variable
 * @package Code_Alchemy\Vendors\Twilio
 *
 * Substitutes a Twilio Variable for its parsed value
 */
class Twilio_Variable extends Stringable_Object{

    public function __construct( $twilio_variable_name ){

        $value = "$twilio_variable_name: Code Alchemy does not recognize this Twilio Variable.";

        switch ( $twilio_variable_name ){

            case '@Twilio.From': $value = isset($_REQUEST['From'])?$_REQUEST['From']:"$twilio_variable_name: Value not set.";  break;
        }

        $this->string_representation = $value;
    }

}