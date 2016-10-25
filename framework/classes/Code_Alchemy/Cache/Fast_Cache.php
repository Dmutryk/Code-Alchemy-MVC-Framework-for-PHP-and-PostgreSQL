<?php


namespace Code_Alchemy\Cache;


use Code_Alchemy\Core\Array_Representable_Object;

class Fast_Cache extends Array_Representable_Object {

    /**
     * @var int Cache size
     */
    private $cache_size = 10;

    /**
     * Construct a new Cache with a Size
     * @param int $cache_size defaults to 10
     */
    public function __construct( $cache_size = 10 ){

        // Persists Cache size
        $this->cache_size = $cache_size;

    }

    /**
     * @param $key
     * @return bool
     */
    public function exists( $key ){

        return isset( $this->array_values[ $key ]);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get( $key ){

        //if ( $this->is_development() ) \FB::info(get_called_class().": Nice!  Reusing Cache element $key");

        return @$this->array_values[ $key ];

    }

    public function set( $key, $value ){

        // If full..
        if ( count( $this->array_values) == $this->cache_size )

            // shift one off
            array_shift( $this->array_values );

        // Now add it
        $this->array_values[ $key ] = $value;

    }

    /**
     * @param $cache_key
     * @param callable $build_function
     * @return mixed
     */
    public function build_and_return( $cache_key, callable $build_function, array $callback_parameters ){

        // Build it
        $this->array_values[ $cache_key ] = call_user_func($build_function,$callback_parameters);

        // Return it
        return $this->array_values[ $cache_key ];

    }

    /**
     * Invalidate the entire cache
     */
    public function invalidate_cache(){

        unset ( $this->array_values );

        $this->array_values = null;

        $this->array_values = array();

    }

}