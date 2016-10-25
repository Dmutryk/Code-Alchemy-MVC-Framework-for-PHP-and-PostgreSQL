<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/10/15
 * Time: 3:02 PM
 */

namespace Code_Alchemy\Vendors\Twilio;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Text_Operators\Text_Template;

class Twilio_Response_Object extends Text_Template {

    public function __construct( $twilio_template, array $data = array()){

        parent::__construct($twilio_template, $data);

    }

}