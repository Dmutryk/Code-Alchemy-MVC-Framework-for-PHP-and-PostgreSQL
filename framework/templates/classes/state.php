<?php
/**
 * This component allows you to generate and make available view-specific
 * State information
 */

namespace _mynamespace_\components;
use __mynamespace__\beans\security_manager;
use xobjects;


class state extends xobjects\core\application_state {

    /**
     * @param string $key to indicate which page or view is being used
     */
    public function __construct( $key = 'home' ){

        // Set Key
        $this->key = $key;

    }

    /**
     * @return bool true if User is logged in
     */
    public function is_logged_in(){

        return !! security_manager::fetch()->is_admitted();

    }


    /**
     * @return string Open Graph URL
     */
    public function open_graph_url(){

        $s = $_SERVER;

        return $s['REQUEST_SCHEME']."://".$S['HTTP_HOST'].$S['REQUEST_URI'];

    }


    /**
     * @return string Meta Description for page
     */
    public function meta_description(){

        return "Page description. No longer than 155 characters.";

    }

    /**
     * @return string Page Title for current context
     */
    public function page_title(){

        return "My Application";

    }

    /**
     * @return string Facebook App Id
     */
    public function facebook_app_id(){

        return (string) \x_objects::instance()->configuration()->facebook->app_id;
    }

    /**
     * @return string Google Plus Client ID
     */
    public function google_plus_client_id(){

        return (string) \x_objects::instance()->configuration()->google->client_id;


    }

    /**
     * @param bool $invert swatch colors?
     * @param bool $alternate swatch color?
     * @return string Theme Swatch CSS Class, based on selection
     */
    public function theme_swatch( $invert = false, $alternate = false ){

        $primary = $alternate? 'green':'red';

        $secondary = 'white';

        return $invert ? "swatch-$secondary-$primary": "swatch-$primary-$secondary";

    }

}