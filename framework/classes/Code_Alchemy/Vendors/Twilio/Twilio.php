<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 2:24 PM
 */

namespace Code_Alchemy\Vendors\Twilio;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Twilio
 * @package Code_Alchemy\Vendors\Twilio
 *
 * Twilio connector service class
 */
class Twilio extends Alchemist{

    /**
     * Say something, in response to a voice call
     * @param Twilio_Statement $what_to_say
     */
    public function say( Twilio_Statement $what_to_say ){

        header("content-type: text/xml");

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

        echo (string) $what_to_say;


    }

    /**
     * Respond to a voice call
     */
    public function respond( /* variable length arguments  */ ){

        $response = '';

        foreach ( func_get_args() as $argument )

            if ( ! is_subclass_of($argument,'\\Code_Alchemy\\Vendors\\Twilio\\Twilio_Response_Object')){

                \FB::error(get_called_class().": ".__FUNCTION__.": Each argument must be a Twilio Response Object");

                break;
            } else

                $response .= (string) $argument;

        header("content-type: text/xml");

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

        echo (string) "<Response>$response</Response>";


    }

    /**
     * Require a keypress to continue
     * @param string $required_key
     * @param string $fail_location
     * @return Twilio
     */
    public function require_keypress( $required_key, $fail_location = '/'){

        if ( $_REQUEST['Digits'] != $required_key )

            header("Location: $fail_location");

        return $this;

    }

    /**
     * Dial client by registered name
     * @param string $client_name
     */
    public function dial_client_by_name( $client_name ){

        header("content-type: text/xml");

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

        echo (string) "<Response><Dial><Client>$client_name</Client></Dial></Response>";


    }

}