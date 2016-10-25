<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/14/16
 * Time: 7:57 PM
 */

namespace Code_Alchemy\APIs\Mail_Service\Actors;


use Code_Alchemy\APIs\Mail_Service\Mail_Service_API_Client;
use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Send_Soon
 * @package Code_Alchemy\APIs\Mail_Service\Actors
 *
 * Send an email as soon as possible (usually within one minute)
 */
class Send_Email_Soon extends Array_Representable_Object{

    /**
     * Send_Soon constructor.
     * @param $template_key
     * @param array $recipients
     * @param array $data
     * @param string $sender
     */
    public function __construct( $template_key, array $recipients, array $data, $sender = 'alquemedia-sas' ) {

        $this->array_values = (new Mail_Service_API_Client())

            ->send_soon($sender,$recipients,$template_key,$data);

    }

}