<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 12/14/14
 * Time: 8:05 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\helpers;


class html_email_message {

    /**
     * @var bool result of send
     */
    public $result = false;

    /**
     * @var \xo_email_template Email Template
     */
    private $template = null;

    /**
     * @var \xo_email_message Email Message
     */
    private $message = null;

    /**
     * @var bool debug or not when sending
     */
    private $debug = false;

    /**
     * @var string server name when sending
     */
    private $server_name = '';

    /**
     * @var string From name when sending
     */
    private $from_name = '';

    /**
     * @var string Error when sending
     */
    public $error = '';

    /**
     * Create, and send, an HTML Email Message, given a template
     * and other parameters
     * @param string $from_email
     * @param string $to_email
     * @param string $template_name
     * @param array $template_data
     * @param string $from_name
     * @param string $server_name
     * @param bool $send_immediately
     * @param string $reply_to
     * @param string $copy_to
     * @param bool $debug
     */
    public function __construct(
        $from_email,
        $to_email,
        $template_name,
        $template_data,
        $from_name = '',
        $server_name = '',
        $send_immediately = false,
        $reply_to = '',
        $copy_to = '',
        $debug = false
    ){

        $this->template = new \xo_email_template($template_name,$template_data);

        $this->message = new \xo_email_message($from_email,$to_email,$this->template->subject,(string)$this->template,$reply_to,$copy_to);

        $this->debug = $debug; $this->server_name = $server_name;
        $this->from_name = $from_name;

        if ( $send_immediately ) $this->send();

    }

    /**
     * Send the message
     */
    public function send(){

        $this->result = $this->message->send(
            'phpmailer',
            $this->debug,
            $this->server_name,
            $this->from_name

        );

        $this->error = $this->message->last_error;
    }

}