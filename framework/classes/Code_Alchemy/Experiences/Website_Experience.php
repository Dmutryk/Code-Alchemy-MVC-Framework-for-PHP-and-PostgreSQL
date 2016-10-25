<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 7/30/15
 * Time: 5:08 PM
 */

namespace Code_Alchemy\Experiences;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Helpers\Called_Class;

/**
 * Class Website_Experience
 * @package Code_Alchemy\Experiences
 *
 * A Website Experience is a collection of events, behaviors, controls and activities
 * related to an experience associated with interacting with a Website.
 *
 */
abstract class Website_Experience extends Array_Representable_Object {

    /**
     * @var string Cookie Key
     */
    private $cookie_key = '';


    /**
     * @param array $options to set
     */
    public function __construct(array $options = array()){

        // Set Cookie Key
        $this->cookie_key = 'ca_'.new Called_Class( $this ) .'_'. implode('_',explode('.',$_SERVER['HTTP_HOST']));

    }

    /**
     * @return bool true if saved, that is, if cookie was created or updated
     */
    public function save(){

        // Set a cookie with user values
        $result = setcookie($this->cookie_key, serialize($this->user_values), time() + 14 * 86400, '/');

        return $result;


    }

    /**
     * Load from Cookie
     */
    public function load(){

        $cookie_key = $this->cookie_key;

        if ( isset( $_COOKIE[$cookie_key]))

            $this->user_values = unserialize($_COOKIE[$cookie_key]);

    }

    /**
     * @param $lookup_key
     * @return mixed User value, if it exists
     */
    public function user_value( $lookup_key ){

        return isset( $this->user_values[ $lookup_key ])?

            $this->user_values[ $lookup_key ] : null;
    }

    /**
     * Set a user value
     * @param $lookup_key
     * @param $value
     * @return $this
     */
    public function set_user_value( $lookup_key, $value ){

        $values = $this->user_values;

        $values[ $lookup_key ] = $value;

        $this->user_values = $values;

        return $this;
    }

    public function clear_user_values_by_pattern( $regex_pattern ){

        $values = $this->user_values;

        foreach ( $values as $name => $value )

            if ( preg_match( $regex_pattern,$name ) )

                unset( $values[$name]);

        $this->user_values = $values;

        return $this;
    }

    /**
     * @return $this
     */
    public function reset(){

        $this->user_values = [];

        return $this;
    }

}