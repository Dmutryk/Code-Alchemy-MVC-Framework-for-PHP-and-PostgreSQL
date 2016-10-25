<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/12/15
 * Time: 1:50 PM
 */

namespace Code_Alchemy\Email;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Email\Helpers\Deferred_Message_Data;
use Code_Alchemy\Email\Helpers\Deferred_Message_Email;
use Code_Alchemy\Models\Interfaces\Model_Interface;
use Code_Alchemy\Models\Model;

/**
 * Class Send_Deferred_Message
 * @package Code_Alchemy\Email
 *
 * Sends a deferred message
 */
class Send_Deferred_Message extends Array_Representable_Object {

    /**
     * Send_Deferred_Message constructor.
     * @param Model $deferred
     * @param $from_email
     * @param $from_name
     * @param bool|false $verbose
     */
    public function __construct( Model_Interface  $deferred, $from_email, $from_name, $verbose = false ){

        if ( $verbose ) echo get_called_class().": Sending message $deferred->id\r\n";

        $template = (new Model('email_template'))

            ->find("id='$deferred->email_template_id'");

        $messenger = (new Email_Messenger(

            $template->template_key, (new Deferred_Message_Data($deferred))->as_array()

        ));


        $this->result = $messenger->send_to((string)new Deferred_Message_Email($deferred),$from_email,$from_name)

            ?'success':'error';

        $this->error = $messenger->error;


        $deferred->update(array(

            'is_sent' => true,

            'is_error' => $this->result == 'error',

            'error' => $this->error

        ))->put();

    }
}