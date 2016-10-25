<?php
/**
 * Project:
 * Module:
 * Component:
 * Description:
 * Author:
 * Copyright:
 */

namespace Code_Alchemy;


class disk_cache {

    const default_age_minutes = 60;

    /**
     * @var int default age in minutes to expire record
     */
    private $age_minutes = self::default_age_minutes;

    /**
     * @var string name of Model Class for caching
     */
    private $model_name  =  '';

    private $model = null;

    /**
     * Create a new simple Disk Cache
     * @param array $options to set when creating the cache
     * @throws \Exception when Model doesn't exist
     */
    public function __construct($options = array()){

        foreach( $options as $name=>$value)
            if (property_exists(get_class(),$name))
                $this->$name = $value;

        // set the model
        $classname = $this->model_name;

        if ( ! class_exists( $classname ))
            throw new \Exception(get_class() .": $classname: must be a valid Model/Business Object class");
        $this->model = $classname::model();

    }

    /**
     * Set a value in the disk cache
     * @param $what
     * @param $how
     */
    public function __set( $what, $how ){ $this->_set($what,$how); }

    /**
     * Set a new member in the database
     * @param $what
     * @param $how
     */
    private function _set( $what, $how ){

        // remove any existing ones
        $aged = new $this->model_name("key='$what'");

        if ( $aged->exists){

            $aged->delete();

        }

        $cache = new $this->model_name;
        $cache->key = $what;

        // if array, serialize; otherwise just save as usual
        $cache->value = is_array($how)? serialize($how):(string)$how;

        $cache->save();

    }

    /**
     * @param string $what to get
     * @return mixed what
     */
    public function __get( $what ){ return $this->_get( $what , $this->model ); }

    /**
     * @param string $what
     * @param \xo_model $model class
     * @return mixed result
     */
    private function _get( $what, $model ){

        $candidate = $model->find_first( "key='$what'");

        // age it out
        $age = abs(time() - strtotime($candidate->added_date)) / 60;
        if ( $candidate->exists && $age > $this->age_minutes){
            if ( method_exists($candidate,'log_age_event'))
                $candidate->log_age_event($age);
            $candidate->delete();
            $candidate = null;
        }

        return $candidate && $candidate->exists ? $this->possibly_unserialize($candidate->value) : null;

    }

    /**
     * If necessary, unserialize a value
     * @param string $value
     * @return mixed
     */
    private function possibly_unserialize( $value ){

        $unserialized = unserialize( $value );

        return $unserialized === false ? $value : $unserialized;

    }

}