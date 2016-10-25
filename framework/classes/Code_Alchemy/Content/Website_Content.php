<?php


namespace Code_Alchemy\Content;


use Code_Alchemy\Models\Dynamic_Model;

class Website_Content {

    /**
     * @var string Content fetched from database
     */
    private $content = '';

    /**
     * @param $state_key
     * @param $content_key
     */
    public function __construct( $state_key, $content_key ){

        $this->content = (new Dynamic_Model('codealchemy_content'))->find("state_key='$state_key',content_key='$content_key'")->get('content_text');

       // \FB::info($this->content);
    }

    /**
     * @param bool $strip_tags
     * @return null|string
     */
    public function content( $strip_tags = false ){

        return $strip_tags ? strip_tags($this->content): $this->content;

    }

}