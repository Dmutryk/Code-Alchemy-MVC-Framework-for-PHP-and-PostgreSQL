<?php


namespace Code_Alchemy\Content;


use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Core\Stringable_Object;

class Page_Title extends Stringable_Object {

    /**
     * @param string $default_title for Page
     */
    public function __construct( $default_title = 'Default Page Title' ){

        $this->string_representation = $default_title;

        $config = new Configuration_File();

        $settings = $config->settings_for( 'page_title');

        //\FB::log($settings);

    }

}