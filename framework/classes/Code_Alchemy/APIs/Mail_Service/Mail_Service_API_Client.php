<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/17/16
 * Time: 9:49 AM
 */

namespace Code_Alchemy\APIs\Mail_Service;


use Code_Alchemy\APIs\Scaffolding\Web_Services_API_Client;

/**
 * Class Mail_Service_API_Client
 * @package Code_Alchemy\APIs\Mail_Service
 *
 * API Client for using Mail Services
 */
class Mail_Service_API_Client extends Web_Services_API_Client {

    public function __construct() {

        parent::__construct( 'email-service' );

    }

    /**
     * @param string $source_key
     * @param array $recipients
     * @param $template_key
     * @param array $template_data
     * @return mixed
     */
    public function send_immediately( $source_key, array $recipients, $template_key, array $template_data ){

        return $this->_send(true,$source_key,$recipients,$template_key,$template_data);

    }

    /**
     * Send soon, but not immediately
     * @param string $source_key to lookup source record
     * @param array $recipients
     * @param $template_key
     * @param array $template_data
     * @return mixed
     */
    public function send_soon( $source_key, array $recipients, $template_key, array $template_data ){

        return $this->_send(false,$source_key,$recipients,$template_key,$template_data);

    }

    /**
     * Send soon, as text, but not immediately
     * @param string $source_key to lookup source record
     * @param array $recipients
     * @param $template_key
     * @param array $template_data
     * @return mixed
     */
    public function send_soon_as_text( $source_key, array $recipients, $email_subject, $email_body, array $attachments = [] ){

        return $this->_send_as_text(false,$source_key,$recipients,$email_subject,$email_body, $attachments);

    }

    /**
     * @param $is_immediate
     * @param string $source_key
     * @param array $recipients
     * @param $template_key
     * @param array $template_data
     * @param array $attachments
     * @return mixed
     */
    private function _send( $is_immediate, $source_key, array $recipients, $template_key, array $template_data, array $attachments = [] ){

        $result = $this->_curl_invocation( 'send', array(

            'to_email' => $recipients,

            'template_data' => $template_data,

            'source_key' => $source_key,

            'template_key' => $template_key,

            'is_immediate' => $is_immediate,

            'attachments' => $attachments

        ), $this->_debug );

        return $result;

    }

    /**
     * Send as text
     * @param $is_immediate
     * @param $source_key
     * @param array $recipients
     * @param $email_subject
     * @param $email_body
     * @param array $attachments
     * @return mixed
     */
    private function _send_as_text( $is_immediate, $source_key, array $recipients, $email_subject, $email_body, array $attachments = []){

        $result = $this->_curl_invocation( 'send_as_text', array(

            'to_email' => $recipients,

            'email_subject' => $email_subject,

            'source_key' => $source_key,

            'email_body' => $email_body,

            'is_immediate' => $is_immediate,

            'attachments' => $attachments

        ), $this->_debug );

        return $result;


    }

}