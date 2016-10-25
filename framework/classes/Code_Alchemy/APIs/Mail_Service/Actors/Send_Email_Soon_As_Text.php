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
class Send_Email_Soon_As_Text extends Array_Representable_Object{

    /**
     * Send_Email_Soon_As_Text constructor.
     * @param $email_subject
     * @param $email_body
     * @param array $recipients
     * @param string $sender
     * @param array $attachments
     */
    public function __construct( $email_subject, $email_body, array $recipients, $sender = 'alquemedia-sas', array $attachments = [] ) {

        $this->array_values = (new Mail_Service_API_Client())

            ->send_soon_as_text($sender,$recipients,$email_subject, $email_body,$attachments);

    }

}