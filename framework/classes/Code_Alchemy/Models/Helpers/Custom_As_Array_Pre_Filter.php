<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Array_Representable_Object;


class Custom_As_Array_Pre_Filter extends Array_Representable_Object {

    /**
     * @var Fast_Cache
     */
    private static $cache = null;

    /**
     * @param $cache_key
     * @return bool true if item present in cache
     */
    protected function get_cached( $cache_key, callable $build_item, array $callback_parameters ){

        $this->initialize_cache();

        return self::$cache->exists( $cache_key ) ?

            self::$cache->get( $cache_key ) :

            self::$cache->build_and_return( $cache_key, $build_item, $callback_parameters );

    }

    /**
     * Initialize Cache as needed
     */
    private function initialize_cache(){

        if ( ! self::$cache ) self::$cache = new Fast_Cache(50);

    }

}