<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/16/15
 * Time: 10:55 PM
 */

namespace Code_Alchemy\Database\Result;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Postgres\PostgreSQL_Table_Info_SQL;

/**
 * Class Query_Result
 * @package Code_Alchemy\Database\Result
 *
 * Abstraction of a database result, from any kind of database
 *
 */
class Query_Result extends Alchemist{
    /**
     * @var \Code_Alchemy\Cache\Fast_Cache
     */
    private static $cache;


    // The "real" result
    private $real_result;

    /**
     * @var string Table Name when necessary
     */
    private $table_name = '';

    /**
     * @param $real_result
     * @param string $table_name
     */
    public function __construct( $real_result, $table_name = ''){

        $this->real_result = $real_result;

        $this->table_name = $table_name;

    }

    /**
     * @return array of results
     */
    public function fetch_assoc(){
        return (get_class($this->real_result) == 'mysqli_result')

            ? $this->real_result->fetch_assoc():

            $this->normalize_postgres_resultset($this->real_result->fetchAll(\PDO::FETCH_ASSOC))

            ;



    }

    /**
     * @param array $original_resultset
     * @return array subset
     */
    private function normalize_postgres_resultset( array $original_resultset ){

        return $original_resultset;
    }

    /**
     * @return array of fields
     */
    public function fetch_fields(){

        $fetched_fields = (get_class($this->real_result) == 'mysqli_result')

            ? $this->real_result->fetch_fields() : $this->pg_fetch_fields();

        return $fetched_fields;



    }

    /**
     * TODO CHRISTIAN
     * @return array of fields from PG table
     */
    private function pg_fetch_fields(){

        // TODO table name
        $table_name = $this->table_name;

        // Initialize Cache
        if ( ! self::$cache ) self::$cache = new Fast_Cache();

        // Get Column names
        $names = array();

        // Query to find Columns
        $query = (string) (new PostgreSQL_Table_Info_SQL($table_name));

        // is it in the cache already?
        $cache_key = (string)new Cache_Key($query);

        if ( self::$cache->exists($cache_key) )

            // use that value
            return self::$cache->get( $cache_key );

        // otherwise fetch em
        else {

            $result = (new Database)->query($query);

            return $result->fetchAll( \PDO::FETCH_CLASS );

        }

    }

}
