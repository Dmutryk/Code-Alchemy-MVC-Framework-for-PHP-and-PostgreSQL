<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/6/15
 * Time: 5:43 PM
 */

namespace Code_Alchemy\Email;


use Code_Alchemy\Views\Helpers\Handlebars_Engine;

class Handlebars_Email_Messenger extends Messenger_Core {

    /**
     * @return string email body, after having been resolved by template
     */
    public function emailBody(){ return $this->handlebars_result; }

    /**
     * @param $template_key
     * @param $data
     * @param $email_subject
     * @param bool|false $debug
     */
    public function __construct( $template_key, $data, $email_subject, $debug = false ){

        $this->debug = $debug;

        $this->email_subject = $email_subject;

        $this->handlebars_result = (new Handlebars_Engine())->render($template_key,$data);

    }


}