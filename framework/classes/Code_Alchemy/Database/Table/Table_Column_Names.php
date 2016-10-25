<?php


namespace Code_Alchemy\Database\Table;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Result\Fetch_Fields;
use Code_Alchemy\Database\Result\Query_Result;

class Table_Column_Names extends Array_Representable_Object {

    /**
     * @var \Code_Alchemy\Cache\Fast_Cache
     */
    private static $cache;

    public function __construct( $table_name ){

        // Initialize Cache
        if ( ! self::$cache ) self::$cache = new Fast_Cache();

        // Get Column names
        $names = array();

        // is it in the cache already?
        $cache_key = (string)new Cache_Key($table_name);

        if ( self::$cache->exists($cache_key) )

            // use that value
            $names = (array) self::$cache->get( $cache_key );

        // otherwise fetch em
        else {

            if ( ( $result = (new Database)->get_field_names($table_name))) {

                // Get those fields
                $names = (array) $result;

                // Save in cache
                self::$cache->set( $cache_key, $names );

            }

        }


        $this->array_values = $names;


    }

}
