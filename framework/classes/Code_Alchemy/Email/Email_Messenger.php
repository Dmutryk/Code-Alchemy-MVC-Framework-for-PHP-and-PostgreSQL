<?php

namespace Code_Alchemy\Email;

/**
 * Class Email_Messenger
 * @package Code_Alchemy\Email
 */
class Email_Messenger extends Messenger_Core {

    /**
     * @param $message_key
     * @param $data
     * @param bool|false $debug
     * @param string $key_field
     */
    public function __construct( $message_key, $data, $debug = false, $key_field = 'template_key' ){

        $this->debug = $debug;

        $this->template = new Email_Template($message_key,$data,$key_field);

    }


}