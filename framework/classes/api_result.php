<?php
/**
 * Project:             X-Objects, MVC Web and Mobile Web Applications Framework
 * Module:              JSON-response API Services
 * Component:           API Result
 * Description:         Abstract representation of the Result object, returned by
 *                      any API Service, and thus allowing for echo as JSON back to the client
 *
 * Author:              David Greenberg <david@reality-magic.com>
 * Copyright:           (c) 2013 X-Objects Foundation. Subject to MIT Open Source License
 */

namespace Code_Alchemy;


abstract class api_result {

    /**
     * @var array of visible result members, for display as JSON
     */
    protected $rest_result = array();

    /**
     * Get a key value
     * @param $key
     * @return mixed
     */
    public function __get($key){
        return @$this->rest_result[$key];
    }

    /**
     * Set a new value for the Result
     * @param string $key
     * @param string $value
     */
    public function __set($key,$value){
        $this->rest_result[$key]=$value;
    }

    /**
     * @return array representation of Result
     */
    public function as_array(){

        // clean up any null members
        foreach ( $this->rest_result as $member=>$value)
            if ($value === null)
                unset($this->rest_result[$member]);

        return $this->rest_result;

    }

}