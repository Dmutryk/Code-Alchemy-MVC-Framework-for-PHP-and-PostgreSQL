<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 1/6/15
 * Time: 3:34 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\components;


use Code_Alchemy\helpers\model_class_for;
use Code_Alchemy\tools\database;

class page_content {

    /**
     * @var array of Content
     */
    private $content = array();

    /**
     * @var string State Key
     */
    private $state_key = '';

    /**
     * @param string $state_key from State
     */
    public function __construct( $state_key ){

        $this->state_key = $state_key;

        // Fetch content for state
        $this->fetch_content( $state_key );

        // And for Site
        $this->fetch_content( 'site' );

        //\FB::log($this->content);

    }

    /**
     * Fetch content
     * @param string $key of state to fetch
     */
    private function fetch_content( $key ){

    }

    /**
     * Return Content
     * @param string $key to fetch
     * @param bool $strip_tags option to strip away HTML
     * @return string resulting content
     */
    public function fetch( $key, $strip_tags = false ){

        $content = '';

        if ( isset( $this->content[ $key ])) $content = $this->content[ $key ];

        return $strip_tags ? strip_tags($content):$content;

    }



}