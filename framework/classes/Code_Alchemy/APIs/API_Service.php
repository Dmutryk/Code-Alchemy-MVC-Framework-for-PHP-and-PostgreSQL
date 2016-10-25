<?php
/**
 * Project:
 * Module:
 * Component:
 * Description:
 * Author:
 * Copyright:
 */

namespace Code_Alchemy\APIs;


use Code_Alchemy\Core\REQUEST_URI;

abstract class API_Service {

    /**
     * Default index in URL to look for the API method
     */
    const default_method_index = 3;


    /**
     * @var array memory cache for API results
     */
    protected $memory_cache = array();


    /**
     * @var null|disk_cache cache on disk
     */
    protected $disk_cache = null;

    /**
     * @var int How many cache hits were made?
     */
    protected $num_cache_hits = 0;

    /**
     * @var int how many actual API calls were made?
     */
    protected $num_api_calls = 0;


    /**
     * @var null|\REQUEST_URI to access url components
     */
    protected $uri = null;

    /**
     * @var bool result of entire API operation
     */
    protected $result = true;

    /**
     * @var string any most recent error during processing
     */
    protected $error = '';

    /**
     * @var null warnings raised
     */
    protected $warning = null;

    /**
     * @var array of options for instantiating the API
     */
    protected $options = array();

    /**
     * @param array $options to set for the APi service component
     */
    public function __construct($options = array()){

        foreach( $options as $member=>$value)
            $this->options[$member] = $value;

        $this->uri = new REQUEST_URI();

    }

    /**
     * Set standard result wrapper members to a Result Object
     * @param api_result $result
     */
    protected function set_result( &$result ){

        $members = array ( 'result','warning','error');

        foreach ( $members as $member )
            if ( $this->$member !== null)
                $result[$member] =($member == 'result')? ($this->result?'success':'error'):$this->$member;
    }

    /**
     * Fetch a (potentially) cached result, either from memory or from disk
     * @param string $key of cached member
     * @return mixed resulting value
     */
    protected function cached_result($key){

        // first check if in memory cache
        $result = @$this->memory_cache[$key];


        // next check disk cache (if available)
        if ($result === null && $this->disk_cache){

            // fetch it
            $result = $this->disk_cache->$key;

            // add to memory cache for next time
            $this->memory_cache[ $key ] = $result;

        } else {

            if (  $result !== null )
                $this->num_cache_hits++;

        }

        // if we still don't have it, build and add to memory and to disk
        if ( $result === null){

            $result = $this->get_api_result( $key );

            $this->memory_cache[ $key ] = $result;

            if ( $this->disk_cache ) $this->disk_cache->$key = $result;

        } else {

            $this->num_cache_hits++;

        }

        return $result;
    }


    /**
     * Get an API result
     * @param string $key
     * @return mixed result
     */
    abstract public function get_api_result( $key );

    /**
     * Process an API request
     * @return mixed
     */
    abstract public function process_request();
}