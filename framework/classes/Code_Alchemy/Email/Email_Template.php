<?php

namespace Code_Alchemy\Email;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Text_Operators\Text_Template;

/**
 * Class Email_Template
 * @package Code_Alchemy\Email
 */
class Email_Template extends Alchemist{

    /**
     * @var bool if true, debug this component
     */
    protected $debug = false;

    /**
     * @var string Email Subject
     */
    public $subject = '';

    /**
     * @var array of data
     */
    private $data = array();

    /**
     * @param $template_name
     * @param array $data
     * @param string $key_field
     */
    public function __construct($template_name, array $data, $key_field = 'template_key'){

        // Create the template
        $this->template = (new Dynamic_Model('email_template'))->find("$key_field='$template_name'");

        // If found
        if ( $this->template->exists ){

            $this->subject = (string) new Text_Template($this->template->get('subject'),$data);

            $this->text = $this->template->get('text');

            $this->data = $data;

            // Perform replacements
            if ( preg_match_all("/#([a-z|A-Z|0-9|_]+)/",$this->text,$hits)){
                // print_r($hits);
                foreach ($hits[1] as $member){

                    $this->text= preg_replace("/#$member/",$this->data[$member],$this->text);
                }
            }

            if ( preg_match_all("/\{\{([a-z|A-Z|0-9|_]+)\}\}/",$this->text,$hits)){

                foreach ($hits[1] as $member){

                    $this->text= preg_replace("/\{\{$member\}\}/",$this->data[$member],$this->text);
                }
            }

        } else {

            \FB::warn(get_called_class().": $template_name: No such Email Template found");
        }

    }

    public function __toString(){
        return $this->text?$this->text:(string)"";
    }


}
