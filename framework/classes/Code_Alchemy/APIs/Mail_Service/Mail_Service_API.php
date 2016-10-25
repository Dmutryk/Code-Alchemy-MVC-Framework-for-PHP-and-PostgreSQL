<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/26/15
 * Time: 9:38 PM
 */

namespace Code_Alchemy\APIs\Mail_Service;


use Code_Alchemy\APIs\Helpers\Node;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Email\Email_Messenger;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Vendors\PHPMailer\PHPMailer_Credentials;

/**
 * Class Mail_Service_API
 * @package Code_Alchemy\APIs\Mail_Service
 *
 * A Mail Service API implementation, which allows authorized clients
 * to send messages and create/modify source addresses and templates
 */
class Mail_Service_API extends Array_Representable_Object {

    /**
     * @var string Node is what we want to hit
     */
    private $node = '';

    /**
     * @var string Access Token
     */
    private $access_token = '';

    /**
     * @var int Id of client
     */
    private $client_id = 0;

    /**
     * @var array of data for template
     */
    private $data = array();


    public function __construct( array $client_data  ){

        // Set data
        $this->data = isset( $client_data['data'])?

            json_decode(urldecode($client_data['data'] ),true): array();


        // Set token
        $this->access_token = isset( $client_data['_token'] )?

            (string)$client_data['_token'] : '';

        // Set Node
        $this->node = (string) new Node();

        // No node?
        if ( ! $this->node ){

            $this->result = 'error';

            $this->error = "No node was specified.  I don't know what to do.";

        } else {

            if ( method_exists($this,$this->node) ){

                $method_name = $this->node;

                $this->$method_name( $client_data );

            } else {

                $this->result = 'error';

                $this->error = "$this->node: I don't recognize that node.";

            }



        }

        // Set signature
        $this->signature = $this->signature();

    }

    /**
     * Send an Email
     * @param array $client_data
     */
    private function send( array $client_data ){

        //$this->client_data = $client_data;

        if ( ! $this->is_authorized( ) ){

            $this->result = 'error';

            $this->error = "Not authorized.";

        } else {

            $this->template_key  = $client_data['template_key'];

            $find_template_Str = "client_id='$this->client_id',template_key='" . $client_data['template_key'] . "'";

            //$this->find_templ = $find_template_Str;


            $email_template =

                (new Model('email_template'))

                    ->find($find_template_Str);

            $send_request = (new Model('send_request'))

                ->create_from(array_merge($client_data,array(

                    'client_id' => $this->client_id,

                    'source_id' => $this->_source( $client_data ),

                    'email_template_id' => $email_template->id(),

                    'created_by' => 1,

                    'attachments' => is_array($client_data['attachments'])? implode(',',$client_data['attachments']): null

                )));

            // Didn't save?
            if ( ! $send_request->exists ){

                $this->result = 'error';

                $this->error = "Invalid send request: ".$send_request->error();

                $this->source_key = $client_data['source_key'];

            } else {

                    $source = (new Model('source'))

                        ->find("source_id='".$this->_source($client_data)."'");

                    $num_queued = 0;

                    foreach ( $this->get_recipients($client_data) as $recipient ){

                        $srr = (new Model('send_request_recipient'))

                            ->create_from(array(

                                'email' => $recipient,

                                'send_request_id' => $send_request->id(),

                                'created_by' => 1

                        ));

                        if ( ! $srr->exists )

                            $this->srr_error = $srr->error();

                        $email_Messenger = (new Email_Messenger($email_template->template_key, $this->_template_data( $client_data)));



                        if ( $send_request->get('is_immediate')){

                            // Use Credential when necesary
                            $source_id = $this->_source( $client_data );

                            $this->source_id = $source_id;

                            $cred_Model = (new Model('phpmailer_credential'))

                                ->find("source_id='". $source_id ."'");

                            if ( $cred_Model->exists ){

                                $email_Messenger->set_credentials( new PHPMailer_Credentials($cred_Model->get('smtp_server'),

                                    $cred_Model->get('smtp_username'),$cred_Model->get('smtp_password')));


                                $this->using_credentials = true;
                            }

                            $this->from_email = $source->email;

                            $this->from_name = $source->name;

                            $send_result = $email_Messenger

                                ->send_to($recipient,$source->email,$source->name);

                            $this->result = $send_result ? 'success':'error';

                            $this->error = $email_Messenger->error;

                            $this->template_data = $this->_template_data( $client_data );

                            if ( $this->error ) {

                                $this->source_key = $client_data['source_key'];


                                $this->result = 'error';

                                break;
                            } else {

                                $srr->update(array(

                                    'is_sent' => true,

                                    'send_date' => date('Y-m-d H:i:s')

                                ))->put();
                            }


                        } else {

                            // Save Parsed template
                            $send_request->update(array(

                                'parsed_template' => (string) $email_Messenger->email_template()

                            ))->put();

                            $num_queued++;
                        }


                    }

                $this->queued_to_send = $num_queued;

                if ( $num_queued > 0 ) $this->result = 'success';

            }
        }

    }


    /**
     * Send an Email
     * @param array $client_data
     */
    private function send_as_text( array $client_data ){

        if ( ! $this->is_authorized( ) ){

            $this->result = 'error';

            $this->error = "Not authorized.";

        } else {

            // Email Subject
            $email_subject  = $client_data['email_subject'];

            $email_body = $client_data['email_body'];

            $this->attachments = $client_data['attachments'];


            $send_request = (new Model('send_request'))

                ->create_from(array_merge($client_data,array(

                    'client_id' => $this->client_id,

                    'source_id' => $this->_source( $client_data ),

                    'email_subject' => $email_subject,

                    'parsed_template' => $email_body,

                    'created_by' => 1,

                    'attachments' => is_array($client_data['attachments'])?implode(',',$client_data['attachments']): ''

                )));

            // Didn't save?
            if ( ! $send_request->exists ){

                $this->result = 'error';

                $this->error = "Invalid send request: ".$send_request->error();

                $this->source_key = $client_data['source_key'];

            } else {

                $source = (new Model('source'))

                    ->find("source_id='".$this->_source($client_data)."'");

                $num_queued = 0;

                foreach ( $this->get_recipients($client_data) as $recipient ){

                    $srr = (new Model('send_request_recipient'))

                        ->create_from(array(

                            'email' => $recipient,

                            'send_request_id' => $send_request->id(),

                            'created_by' => 1

                        ));

                    if ( ! $srr->exists )

                        $this->srr_error = $srr->error();

                    //$email_Messenger = (new Email_Messenger($email_template->template_key, $this->_template_data( $client_data)));



                    if ( $send_request->get('is_immediate')){

                        $this->result = 'error';

                        $this->error = "Immediate send not supported for pre-parsed email messages";

                    } else {

                        $num_queued++;
                    }

                }

                $this->queued_to_send = $num_queued;

                if ( $num_queued > 0 ) $this->result = 'success';

            }
        }

    }



    /**
     * @param array $client_data
     * @return int Source Id
     */
    private function _source( array $client_data ){

        return isset( $client_data['source_id']) ? $client_data['source_id']:

        (new Model('source'))->find("seo_name='".$client_data['source_key']."'")->id();

    }

    /**
     * @return array template data
     */
    private function _template_data( array $client_data ){

        return is_array( $client_data['template_data']) ? $client_data['template_data']: array();

    }

    /**
     * @param array $client_data
     * @return array of recipients
     */
    private function get_recipients( array $client_data ){

        $recipients = array();

        if (isset( $client_data['to_email'])){

            if ( is_array($client_data['to_email']))

                $recipients = $client_data['to_email'];

            else

                $recipients[] = $client_data['to_email'];
        }

        return $recipients;

    }

    /**
     * Send an Email
     * @param array $client_data
     */
    private function lookup( array $client_data ){

        if ( ! $this->is_authorized( ) ){

            $this->result = 'error';

            $this->error = "Not authorized.";

        } else {

            $target = @$client_data['target'];

            $type = @$client_data['type'];

            $query = @$client_data['query'];

            if ( ! $target || ! $type || ! $query ){

                $this->result = 'error';

                $this->error = "Please specify a target, a query type, and a query";
            } else {

                $found = (new Model($target))

                    ->find("name".($type=='equals' ? '=' : ' LIKE ').

                        ($type=='equals'? "'$query'": " %$query% ")
                    );

                $this->subject = $found->as_array();

            }

        }

    }



    /**
     * @return bool true if authorized
     */
    private function is_authorized(){

        $client = (new Model('client'))

            ->find("access_token='$this->access_token',is_authorized='1'");

        $this->client_id = $client->id();

        return $client->exists;
    }

    /**
     * @return array signature
     */
    private function signature(){

        return array(

            'API' => "Mail Service API",

            "provider" => "Code Alchemy",

            "version" => 1.00,

            'node' => $this->node
        );
    }

}