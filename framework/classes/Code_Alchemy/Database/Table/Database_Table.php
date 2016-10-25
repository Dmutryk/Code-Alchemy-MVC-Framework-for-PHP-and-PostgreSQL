<?php


namespace Code_Alchemy\Database\Table;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Postgres\Is_Postgres_Model;
use Code_Alchemy\Database\Postgres\PostgreSQL_Table_Info_SQL;
use Code_Alchemy\Database\Result\Fetch_Associative_Values;
use Code_Alchemy\Database\Result\Query_Result;

/**
 * Class Database_Table
 * @package Code_Alchemy\Database\Table
 *
 * Represents a Database Table, and fetches information such as
 * columns, keys, foreign keys, etc.
 */
class Database_Table extends Alchemist {

    /**
     * @var bool true if table actually exists in DB
     */
    public $exists = false;

    /**
     * @var string table name
     */
    private $table_name = '';

    /**
     * @var array of table fields
     */
    private $fields = array();

    /**
     * @var array of column names
     */
    private $columns = array();

    /**
     * @var string Key Column
     */
    private $key_column = '';

    /**
     * @var array of foreign keys for this table
     */
    private $foreign_keys = array();

    /**
     * @var \Code_Alchemy\Cache\Fast_Cache|null
     */
    private static $cache = null;

    /**
     * @param string $table_name
     */
    public function __construct( $table_name ){

        // Initialize Cache
        if ( ! self::$cache ) self::$cache = new Fast_Cache();

        $this->table_name = $table_name;

        // Take a guess for Key Column
        $this->key_column = $table_name."_id";

        $this->exists = (new Database())->has_table($table_name);


        if ( $this->exists ){

            // Construct query
            $query = "DESCRIBE `$table_name`";

            // Not allowed for Postgres
            $is_postgres = (new Is_Postgres_Model($table_name))->bool_value();

            if ($is_postgres){

            } else {


                // Get Cache Key
                $cache_key = (string) new Cache_Key($query);

                // if in Cache
                if ( self::$cache->exists( $cache_key ) )

                    // Use cached version
                    $this->parse_data( self::$cache->get( $cache_key ));

                else {

                    $data = array();

                    if ( ($result = (new Database())->query($query))){

                        while ($row =

                            (new Fetch_Associative_Values( new Query_Result($result)))

                                ->as_array()


                        ){

                            // Add to data
                            $data[] = $row;
                        }

                        // Add to cache
                        self::$cache->set( $cache_key, $data );

                        // parse it
                        $this->parse_data( $data );

                    }



                    else \FB::warn(get_called_class().": $table_name: Unable to fetch table description");

                }
            }



        } else {

            \FB::warn(get_called_class().": $table_name: Table doesn't exist");
        }


    }

    /**
     * Parse the data as required
     * @param array $data
     */
    private function parse_data( array $data ){

        foreach ( $data as $row ){

            $this->fields[] = $row;

            // If foreign key
            if ( $row['Key'] == 'MUL')

                // Add it
            $this->foreign_keys[] = $row['Field'];


            $this->columns[] = $row['Field'];

            if ( $row['Key']=='PRI' && ! $this->key_column )

                $this->key_column = $row['Field'];

        }


    }
    /**
     * @param $name
     * @return bool true if has foreign key
     */
    public function has_foreign_key( $name ){

        return !! ( in_array($name,$this->foreign_keys));

    }

    /**
     * @return string
     */
    public function name(){ return $this->table_name; }

    /**
     * @return string key column
     */
    public function key_column(){ return $this->key_column; }

    /**
     * @return string columns
     */
    public function columns(){ return $this->columns; }

    /**
     * @return bool true if safe delete is supported
     */
    public function supports_safe_delete(){

        $in_array = in_array('is_deleted', $this->columns());

        return $in_array;

    }

    /**
     * @return string Guess of reference column
     */
    public function guess_reference_column(){

        $column = '';

        $guesses = array('name','title','full_name','caption');

            foreach ( $guesses as $guess)

                if ( in_array($guess,$this->columns)){

                    $column = $guess;

                    break;
                }

        return $column;
    }

    /**
     * @param bool|false $exclude_audit_columns
     * @return array of foreign keys
     */
    public function foreign_keys( $exclude_audit_columns = false ){

        $foreign_keys = array();

        foreach ( $this->foreign_keys as $key )

            if ( ! $exclude_audit_columns || ! in_array($key,array('created_by','last_modified_by','deleted_by')))

                $foreign_keys[] = $key;


        return $foreign_keys;
    }

}