<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 29/09/14
 * Time: 05:10 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Database\Adapters;


use Code_Alchemy\Applications\Toolboxes\Command_Line;
use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Database\Empty_Result;
use Code_Alchemy\Database\SQL\Fetch_SQL;
use Code_Alchemy\Database\SQL\Find_SQL;
use Code_Alchemy\Database\SQL\Insert_SQL;
use Code_Alchemy\Database\Table\Table_Column_Names;
use Code_Alchemy\Models\Helpers\Key_Column_For;

class Adapter_mysql extends Alchemist {

    /**
     * @var \mysqli db connector
     */
    private $mysqli = null;

    /**
     * @var string name of database
     */
    private $name = '';

    /**
     * @var array of Tables
     */
    private $tables = array();

    /**
     * @var bool true for debugging
     */
    private $debug = false;


    private static $cache = null;

    /**
     * @param \stdClass $database
     * @param bool $verbose
     */
    public function __construct($database = null, $name = null ){


        $this->name = $name;

        // Initialize Cache
        if ( ! self::$cache ) self::$cache = new Fast_Cache(100);

        // Normalize if array
        $database = is_array($database)? (new Array_Object($database)):$database;

        // construct conn
        $this->mysqli = @new \mysqli(

            $database->host?$database->host: $database->hostname,

            $database->username,

            $database->password,

            $database->database,

            $database->port ? $database->port : 3306


        );

        if ( $this->mysqli->connect_error ) {

            $this->error = "Not connected to any database 5";

            $this->mysqli = null;

            if ( $this->is_development() )  \FB::error($this->error);

        } else

            $this->mysqli->set_charset('utf8');



    }

    /**
     * @return string last error
     */
    public function error(){ return $this->error; }

    /**
     * @param $query
     * @param bool $is_error
     * @return bool|Empty_Result|\mysqli_result
     */
    public function execute( $query, $is_error = false ){

        $cache_key = (string)new Cache_Key($query);

        // If not in cache
        if ( ! self::$cache->exists($cache_key))

            self::$cache->set($cache_key,$query);

        else {

            //if ( $this->is_development() ) \FB::warn(get_called_class().": Duplicate Query $query");

        }

        if ( ! $this->mysqli ){

            $this->error = 'Not connected to any database 6';

            $is_error = true;

            if ( $this->is_development() ) \FB::error("codeAlchemy is not connected to any database 6");

            return new Empty_Result();

        }


        else {


            $result = $this->mysqli->query( $query );


            if ( ! $result ) {

                $this->error = $this->mysqli->error;

                $is_error = true;

                if ( $this->is_development() ) \FB::error($this->mysqli->error. " ($query)");

                $result = new Empty_Result();

            } else {

                //\FB::info(get_called_class().": Query $query is successful");
            }

        }

        $actual_result = $result;

        return $actual_result;


    }

    public function execute_get_field_names( $table_name ){

        if ( ! $this->mysqli ){

            $this->error = 'Not connected to any database 6';

            $is_error = true;

            if ( $this->is_development() ) \FB::error("codeAlchemy is not connected to any database 6");

            return new Empty_Result();

        }


        else {
            $query = "SELECT * FROM `$table_name` WHERE TRUE LIMIT 1";

            $result = $this->mysqli->query( $query );


            if ( ! $result ) {

                $this->error = $this->mysqli->error;

                $is_error = true;

                if ( $this->is_development() ) \FB::error($this->mysqli->error. " ($query)");

                $result = new Empty_Result();

            } else {

                //\FB::info(get_called_class().": Query $query is successful");
            }

        }

        $names = array();

        foreach ( $result->fetch_fields() as $value )

            $names[] = is_object( $value ) ? $value->name : $value;



        return $names;




    }

    /**
     * @return array of Tables and Views
     */
    public function tables_and_views(){

        if ( $this->debug ) \FB::info(get_called_class().": Tables and Views for $this->name");

        $tableList = array();

        if ( $this->mysqli ){

            $res = $this->mysqli->query("SHOW TABLES");

            if ( $res ){

                if ( $this->debug ) \FB::info($res);

                while( $cRow = $res->fetch_assoc() ){

                    //if ( $this->debug ) \FB::info($cRow);

                    $tableList[] = $cRow["Tables_in_$this->name"];

                }


            }

            else {

                if ( $this->debug ) \FB::warn("Unable to fetch table list: ".$this->mysqli->error);

            }


        } else {

            if ( $this->debug ) \FB::info("Error fetching tables list: Not connected to any database 6");

        }

         //\FB::info($tableList);

        return $tableList;

    }


    /**
     * Get the Enumerable values of a Table Column
     * @param string $table to query
     * @param string $field to query
     * @return array of possible values
     */
    public function get_enum_values( $table, $field){

        $enum = array();

        $result = $this->mysqli->query( "SELECT `COLUMN_TYPE` FROM information_schema.`COLUMNS` WHERE `TABLE_NAME` = '$table' AND `COLUMN_NAME` = '$field'" );

        if ( $result ) {

            $fields = $result->fetch_assoc();

            if ( preg_match("/^enum\(\'(.*)\'\)$/", $fields['COLUMN_TYPE'], $matches))

                $enum = explode("','", $matches[1]);

        } else {

            //\FB::log('nope');

        }

        return $enum;
    }



    /**
     * @param $table_name
     * @param $values
     * @return array of row values inserted, if successful
     */
    public function execute_save_to_table( $table_name, $values ){
        $arr = array();

        $result = $this->execute(
            (string) new Insert_SQL($table_name,
                                    (new Table_Column_Names($table_name))->as_array(),$values)

        );

        if ( $result || (get_class($result)!= 'Code_Alchemy\Database\Empty_Result' )){

            // Get last Id
            $last_id = $this->mysqli->insert_id;

            // Fetch it
            $arr = $this->execute((string) new Fetch_SQL($table_name,(string)new Key_Column_For($table_name),$last_id))->fetch_assoc();

        } else {

            \FB::error($this->error);
        }

        return $arr;

    }

    /**
     * Load a Model from a Table
     * @param $table_name
     * @param $query
     * @param string $comma_substitute
     * @param bool $debug
     * @return array
     */
    public function execute_from_table(
        $table_name,
        $query,
        $comma_substitute = '',
        $debug = false

    ){

        $persistent_values = $this->execute(
            (string)new Find_SQL($table_name, $query,$comma_substitute,$debug),
            $is_error,$comma_substitute
        )->fetch_assoc();

        return $persistent_values;

    }

    /**
     * @param $str
     * @return string
     */
    public function execute_real_escape_string( $str ){

        return $this->mysqli->real_escape_string( $str );
    }

}
