<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/6/15
 * Time: 5:44 PM
 */

namespace Code_Alchemy\Email;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Vendors\PHPMailer\PHPMailer_Credentials;

/**
 * Class Messenger_Core
 * @package Code_Alchemy\Email
 *
 * Messenger Core does heavy lifting for email messaging
 */
class Messenger_Core extends Array_Representable_Object{

    /**
     * @var bool true to send debugging output to Firebug
     */
    protected $debug = true;

    /**
     * @var string error from last operation
     */
    public $error = '';

    /**
     * @var Email_Template to use when sending
     */
    protected $template;

    /**
     * @var string Result of template with Handlebars
     */
    protected $handlebars_result = '';

    /**
     * @var string Email Subject
     */
    protected $email_subject = '';

    /**
     * @var string Message Body
     */
    private $message_body = '';

    /**
     * @var PHPMailer_Credentials
     */
    private $credentials = null;

    /**
     * @var array of attachments to include in message
     */
    protected $attachments = [];


    /**
     * @param string $attachment_path
     */
    public function add_attachment( $attachment_path ){

        $this->attachments[] = $attachment_path;

    }

    /**
     * @param PHPMailer_Credentials $creds
     */
    public function set_credentials( PHPMailer_Credentials $creds ){

        $this->credentials = $creds;

    }

    /**
     * @return string mesage body
     */
    public function message_body(){

        return (string) $this->template;
    }

    /**
     * @param $email_subject
     * @return Messenger_Core
     */
    public function set_email_subject( $email_subject ){

        $this->email_subject = $email_subject;

        return $this;

    }

    /**
     * @param string $message_body
     * @return Messenger_Core
     */
    public function set_message_body( $message_body ){

        $this->message_body = $message_body;

        return $this;

    }

    /**
     * Send the message to a specific email address
     * @param $email
     * @param $from_email
     * @param string $from_name
     * @param string $cc_email
     * @param string $http_host
     * @param string $bcc_email
     * @param array $attachments
     * @param string $replyToEmail
     * @return $this
     */
    public function send_to(
        $email,
        $from_email,
        $from_name = '',
        $cc_email = '',
        $http_host = '',
        $bcc_email = '',
        array $attachments = array(),
        $replyToEmail = ''
    ){

        if ( count( $this->attachments ))

            $attachments = array_merge($attachments, $this->attachments);

        // Get Subject
        $subject = $this->email_subject ? $this->email_subject : $this->template->subject;

        // Get template
        $template =

            $this->message_body ? $this->message_body :

                ($this->handlebars_result ? $this->handlebars_result : (string)$this->template);

        $message = new \xo_email_message(
            $from_email,
            $email,
            $subject, $template,$replyToEmail?$replyToEmail:$from_email,$cc_email);

        if ( $this->credentials )

            $message->set_credentials( $this->credentials );


        $result = $message->send('phpmailer',$this->debug,'',$from_name,$attachments);

        $this->error = $message->last_error;

        $this->all_data = $message->all_data;

        if ( $this->error && $this->is_development() )

            \FB::error(get_called_class().": Error sending email: $this->error");

        $this->array_values = array(
            'result'=>$result?'success':'error',
            'error'=>$this->error,
            'smtp_hostname' => $message->getSMTPHostname()
        );

        return $this;
    }


    /**
     * Send to multiple recipients
     * @param array $email_addresses
     * @param $from_email
     * @param string $from_name
     * @param string $cc_email
     * @param string $http_host
     * @param string $bcc_email
     */
    public function send_to_all(

        array $email_addresses,
        $from_email,
        $from_name = '',
        $cc_email = '',
        $http_host = '',
        $bcc_email = ''

    ){

        foreach ( $email_addresses as $email_address)

            $this->send_to($email_address,$from_email,$from_name,$cc_email,$http_host,$bcc_email);

        return $this;

    }

    /**
     * @return Email_Template
     */
    public function email_template(){ return $this->template; }


}