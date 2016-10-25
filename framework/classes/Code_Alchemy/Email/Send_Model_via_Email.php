<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/6/15
 * Time: 6:31 PM
 */

namespace Code_Alchemy\Email;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Model;

/**
 * Class Send_Model_via_Email
 * @package Code_Alchemy\Email
 *
 * Convenience class for sending a Model via email, when presented
 * using a template, which can either be Handlebars or DB template
 */
class Send_Model_via_Email extends Array_Representable_Object{

    /**
     * @param $template_name
     * @param Model $model
     * @param $to_email
     * @param $from_email
     * @param $from_name
     * @param bool|true $is_handlebars
     * @param string $email_subject
     */
    public function __construct(
        $template_name,
        Model $model,
        $to_email,
        $from_email,
        $from_name,
        $is_handlebars = true,
        $email_subject = ''
){

        $messenger = $is_handlebars ?

            new Handlebars_Email_Messenger($template_name,$model->as_array(),$email_subject) :

            new Email_Messenger($template_name,$model->as_array());

        $result = $messenger->send_to($to_email,$from_email,$from_name);

        $this->array_values = array(

            'to' => $to_email,

            'from' => $from_email,

            'result' => $result ? 'success': 'error',

            'error' => $messenger->error
        );
    }

}