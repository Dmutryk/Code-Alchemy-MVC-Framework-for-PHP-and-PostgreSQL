<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 1/6/15
 * Time: 3:31 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\core;


use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\components\page_content;

class application_state {

    /**
     * @var string key indicating State
     */
    protected $key = 'home';

    /**
     * @return page_content for this State
     */
    public function get_content(){

        static $content = null;

        if ( ! $content ) $content = new page_content( $this->key );

        return $content;

    }

    /**
     * @return string Key for state
     */
    public function key(){

        return $this->key;

    }

    /**
     * @param bool $is_inverted
     * @return string Theme Swatch
     */
    public function theme_swatch( $is_inverted = false ){

        // Assume Class name and location
        $class = "\\".(string) new Namespace_Guess()."\\Helpers\\Theme_Swatch";

        $swatch = ( class_exists($class) ) ? (string) new $class($is_inverted):'';

        return $swatch;

    }

}