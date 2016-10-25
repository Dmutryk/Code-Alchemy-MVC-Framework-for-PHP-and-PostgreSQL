<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/26/15
 * Time: 4:02 PM
 */

namespace Code_Alchemy\Models\Performance;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Factories\Model_Factory;

/**
 * Class Cached_Model_Collection
 * @package Code_Alchemy\Models\Performance
 *
 * Provides a means to Cache collections of Models for better performance
 */
class Cached_Model_Collection extends Array_Representable_Object{

    private static $cache;

    public function __construct( $model_name, $method, $query ){

        // Initialize Cache once
        if ( ! self::$cache ) self::$cache = new Fast_Cache(20);

        // Construct Cache key
        $cache_key = (string) new Cache_Key($model_name."-"."$method"."-"."$query");

        // If already in cache
        if ( self::$cache->exists($cache_key) ){

            //if ( $this->is_development() ) \FB::info(get_called_class().": $cache_key: Already in Cache");

            $this->array_values = self::$cache->get($cache_key);

        } else {

            $models = (new Model_Factory($model_name))->$method( $query );

            // Save in Cache
            self::$cache->set($cache_key,$models);

            $this->array_values = $models;

        }

    }

}