<?php
/**
 * User: "David Owen Greenberg" <code@x-objects.org>
 * Date: 20/11/12
 * Time: 06:07 PM
 *
 * @propertry $subject string the message subject
 * @property $body string the message body
 */
class xo_email_message extends magic_object {

    /**
     * @var string SMTP Hostname
     */
    private $SMTPHostname = '';

    /**
     * @var \Code_Alchemy\Vendors\PHPMailer\PHPMailer_Credentials
     */
    private $credentials = null;

    /**
     * @var string optional reply-to email
     */
    private $reply_to = '';

    public $last_error = '';
    public static $last_class_error = null;

    /**
     * Create a new sendable Email Message
     * @param string $from email
     * @param string $to email
     * @param string $subject of message
     * @param string $body of message
     * @param string $reply_to email
     * @param string $copy_to email
     * @param string $blind_copy_to
     */
    public function __construct(
        $from,
        $to,
        $subject,
        $body,
        $reply_to = '',
        $copy_to = '',
        $blind_copy_to = ''
    ){

        //FB::info($body);

        // Save local values
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->reply_to = $reply_to;
        $this->copy_to = $copy_to;
        $this->blind_copy_to = $blind_copy_to;
    }

    /**
     * Set credentials
     * @param \Code_Alchemy\Vendors\PHPMailer\PHPMailer_Credentials $credentials
     */
    public function set_credentials( \Code_Alchemy\Vendors\PHPMailer\PHPMailer_Credentials $credentials ){

        $this->credentials = $credentials;

    }

    /**
     * @param string $method
     * @param bool|false $debug
     * @param string $server_name
     * @param string $from_name
     * @param array $attachments
     * @return bool
     * @throws phpmailerException
     */
    public function send(
        $method = "phpmailer",
        $debug = false,
        $server_name = '',
        $from_name = '',
        $attachments = array()
    ){
        $result = false;
        $headers = "From: $this->from\r\n".
            //"Reply-To: $this->from\r\n".
            "MIME-Version: 1.0\r\n".
            'Content-Type: text/html; charset="UTF-8"'."\r\n";
        switch ( $method){
            case 'phpmailer':


                $to = $this->to;
                $from = $this->from;
                $mail = new PHPMailer();

                $this->SMTPHostname = $mail->Hostname;

                // If we have reply-to use it
                if ( $this->reply_to )

                    $mail->AddReplyTo($this->reply_to);


                // If credentials
                if ( $this->credentials ) $mail->set_credentials( $this->credentials );

                $mail->SMTPDebug = $debug;
                //$mail->SMTPDebug = true;
                $mail->SetFrom($from,$from_name);
                $mail->Subject = $this->subject;
                $mail->CharSet = 'UTF-8';
                $mail->MsgHTML($this->body);
                $mail->IsHTML(true);
                $mail->isSMTP();


                // Add copy to
                if ( $this->copy_to ) $mail->AddCC($this->copy_to);

                // Add Blind Copy to
                if ( $this->blind_copy_to ) $mail->AddBCC($this->blind_copy_to);

                // If given a server name
                if ( $server_name ){

                    // Set for mailer
                    $mail->Hostname = $server_name;



                }

                if ( count( $attachments))

                    foreach ( $attachments as $attachment)

                        $mail->AddAttachment($attachment);


                try{

                    $mail->AddAddress($to);
                    if($mail->Send()){
                        //echo "succes";
                        $result = true;
                    } else {
                        $this->last_error = $mail->ErrorInfo;
                        $result = false;
                    }
                    $mail->ClearAddresses();

                } catch(phpmailerException $e)
                {
                    FB::error($e->getMessage(),get_called_class());
                }
                catch(Exception $e)
                {
                    FB::error($e->getMessage(),get_called_class());
                }
            break;
        }

        return $result;
    }
    public static function create($from,$to,$subject,$body){
        $c = __CLASS__;
        return new $c($from,$to,$subject,$body);
    }

    /**
     * @return string SMTP Hostname
     */
    public function getSMTPHostname(){ return $this->SMTPHostname; }
}
