<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 29/09/14
 * Time: 05:10 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Database\Adapters;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Database\Adapters\Helpers\Postgres_Backtick_Filter;
use Code_Alchemy\Database\Adapters\Helpers\PostgreSQL_Error_Filter;
use Code_Alchemy\Database\Empty_Result;
use Code_Alchemy\Database\Postgres\Is_Empty_PostgreSQL_Query;
use Code_Alchemy\Database\Postgres\Table_Schema;
use Code_Alchemy\Database\SQL\Fetch_SQL;
use Code_Alchemy\Database\SQL\Find_SQL;
use Code_Alchemy\Database\SQL\Insert_SQL;
use Code_Alchemy\Database\Table\Table_Column_Names;
use Code_Alchemy\Models\Components\Model_Settings;
use Code_Alchemy\Models\Helpers\Key_Column_For;
use Code_Alchemy\Models\Model_Configuration;

/**
 * Class Adapter_pgsql
 * @package Code_Alchemy\Database\Adapters
 *
 */

class Adapter_pgsql extends Alchemist {

    /**
     * @var string
     */
    private $error = '';

    /**
     * @var \pgsql db connector
     */
    private $pgsql = null;

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
    public function __construct($database = null, $name = null, $debug = false ){

        $this->debug = $debug;

        $this->name = $name;

        // Initialize Cache
        if ( ! self::$cache ) self::$cache = new Fast_Cache(100);




        // construct conn
        $str_conn  = "pgsql:host=";
        $str_conn .= isset( $database->host) ?$database->host: @$database->hostname;
        $str_conn .= " port=" .(isset($database->port) ? $database->port: '5432') ." ; ";
        $str_conn .= " dbname=";
        $str_conn .= @$database->database;
        $str_conn .= " user=";
        $str_conn .= @$database->username;
        $str_conn .= " password=";
        $str_conn .= @$database->password;
        
        


        
        
        try {


            $this->pgsql = @new \PDO($str_conn,$database->username,$database->password,array(
                \PDO::ATTR_PERSISTENT => true
            ));

            $this->pgsql->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {

            $this->error = "Not connected to any PostgreSQL database";


            $this->pgsql = null;

            \FB::error(get_called_class().": Error executing query: ".$e->getMessage());
        }



       

    }

    /**
     * @return string last error
     */
    public function error(){ return $this->error; }

    /**
     * @param $query
     * @param bool $is_error
     * @return bool|Empty_Result|\pgsql_result
     */
    public function execute( $query, &$is_error = false ){

        //if ( $this->debug) \FB::info(get_called_class().": Executing Query as $query");

        // query is select?
        $regex = "/^SELECT/i";

        $is_select = !! preg_match($regex,trim($query));

        //found query in cache

        $cache_key = (string)new Cache_Key($query);

        // If not in cache
        if ( ! self::$cache->exists($cache_key))

            self::$cache->set($cache_key,$query);

        else {

            //if ( $this->is_development() ) \FB::info(get_called_class().": DUPLICATE Query $query");

            //return self::$cache->get($cache_key);

        }



        if ( ! $this->pgsql ){

            $this->error = 'Not connected to any PostgreSQL database';

            $is_error = true;

            if ( $this->is_development() ) \FB::error("codeAlchemy is not connected to any database 2");

            return new Empty_Result();

        }


        else {

            $query = ( str_replace(array("`",chr(96)),array('"','"'), $query));
            
            try {

                if($is_select) {

                    if ($this->debug) \FB::info(get_called_class() . ": $query: This is a select query");

                    if ((new Is_Empty_PostgreSQL_Query($query))->bool_value())

                    {
                        //if ( $this->is_development() ) \FB::warn(get_called_class() . ": Empty query rejected by PostgreSQL Adaptor for Code Alchemy, and will not be executed: $query");
                    }

                    else {

                        $result = $this->pgsql->prepare($query);

                        $result->execute();


                    }
                }
                else {

                    if ($this->debug) \FB::info(get_called_class() . ": $query: This is NOT a select query");

                    $result = $this->pgsql->exec($query);

                    if ( $this->debug ) \FB::info($result);

                }


            } catch( \PDOException $pdoe ){

                $is_error = true;

                $sError = $pdoe->getMessage(). " $query";

                $this->error = (string) new PostgreSQL_Error_Filter($sError);

                \FB::error($this->error);
            }

            



            if ( ! @$result ) {


                $this->error = (string) new PostgreSQL_Error_Filter($this->pgsql->errorInfo()[2]);

                $is_error = true;

                $result = new Empty_Result();

            } else {


                if ( $this->debug ) \FB::info(get_called_class().": Query $query is successful");
            }

        }



        $actual_result = $result;

        return $actual_result;


    }

    public function execute_get_field_names( $table_name ){

        if ( ! $this->pgsql ){

            $this->error = 'Not connected to any PostgreSQL database';

            $is_error = true;

            if ( $this->is_development() ) \FB::error("codeAlchemy is not connected to any database 2");

            return new Empty_Result();

        }


        else {

            $query = "SELECT column_name
                    FROM information_schema.columns
                    WHERE table_name = '$table_name';";

            if ( $this->debug ) \FB::info(get_called_class().": Executing Query $query");

            $result = $this->pgsql->prepare( $query );
            $result->execute();

            if ( ! $result ) {

                $this->error = (string) new PostgreSQL_Error_Filter($this->pgsql->errorInfo()[2]);

                $is_error = true;

                if ( $this->is_development() ) \FB::error($this->pgsql->errorInfo()[2]. " ($query)");

                $result = new Empty_Result();

            } else {

                //\FB::info(get_called_class().": Query $query is successful");
            }

        }

        $columns = $result->fetchAll(\PDO::FETCH_COLUMN);

        (new Model_Configuration())->set_model_columns($table_name,$columns);

        return $columns;


    }

    /**
     * @param bool $ignore_cache
     * @return array|mixed|null
     */
    public function tables_and_views( $ignore_cache = false ){

        if ( ! $ignore_cache ){

            // Cache list
            $config = new Model_Configuration();

            if ( $config->find('tables_and_views') && count( $config->find('tables_and_views'))){

                //\FB::info(get_called_class().": Using cached tables and views");

                return $config->find('tables_and_views');

            } else {

                \FB::warn(get_called_class().": No cached version of tables and views found!");
            }

        }



        $tableList = array();


        if ( $this->pgsql ){

            // set table schema
            $table_schema = (string) new Table_Schema();

            $res = $this->execute("select * from information_schema.tables where table_schema='$table_schema'");

            \FB::info(get_called_class().": Executing information schema query");

            if ( $res ){

                $arr = $res->fetchAll();
                foreach ($arr as $row ){

                    $tableList[] = $row[2];

                }


            }

            else {

                if ( $this->debug ) \FB::warn("Unable to fetch table list: ".$this->pgsql->error);

            }


        } else {

            if ( $this->debug ) \FB::info("Error fetching tables list: Not connected to any database 3");

        }

        if ( $this->debug ) \FB::info($tableList);

        if ( $config && ! $config->find('tables_and_views'))

            $config->set('tables_and_views',$tableList)

                ->update();


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

        $result = $this->pgsql->query( "SELECT `COLUMN_TYPE` FROM information_schema.`COLUMNS` WHERE `TABLE_NAME` = '$table' AND `COLUMN_NAME` = '$field'" );

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

        if ( $this->debug ) \FB::info(get_called_class().": Executing save to table $table_name");


        $arr = array();

        $result = $this->execute(
            (string) new Insert_SQL($table_name,
                                    (new Table_Column_Names($table_name))->as_array(),$values)

        );

        if ( $this->debug ) {

            \FB::info(get_called_class().": Result appears next");

            \FB::info(get_class($result));
        }


        if ( $result && ( ! is_object($result) || get_class($result)!= 'Code_Alchemy\Database\Empty_Result' )){

            // Get last Id
            try {

                if ( $this->debug ) \FB::info(get_called_class().": Getting last insert Id");

                $last_id = $this->pgsql->lastInsertId($table_name.'_id_seq');

            } catch ( \PDOException $pdoe ){

                $this->error = (string) new PostgreSQL_Error_Filter($pdoe->getMessage());

                \FB::error(get_called_class().": Postgres PDO Exception: ".$pdoe->getMessage());

            }

            // Fetch it
            //$arr = $this->execute((string) new Fetch_SQL($table_name,(string)new Key_Column_For($table_name),$last_id))->fetch(PDO::FETCH_ASSOC);
            $query = (string) new Fetch_SQL($table_name,(string)new Key_Column_For($table_name),$last_id);
            $query = str_replace(array("`",chr(96)),array('"','"'), $query);

            $arr = $this->pgsql->prepare( $query );

            try {

                $arr->execute();

            } catch ( \PDOException $pdoe ){

                $this->error = (string) new PostgreSQL_Error_Filter($pdoe->getMessage());

            }


        } else {

            \FB::error($this->error);
        }



        return is_object($arr) ? $arr->fetch(\PDO::FETCH_ASSOC): null;

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

        /*$persistent_values = $this->execute(
            (string)new Find_SQL($table_name, $query,$comma_substitute,$debug),
            $is_error,$comma_substitute
        )->fetch_assoc();*/

        $query = (string)new Find_SQL($table_name, $query,$comma_substitute,$debug);
        //\FB::info(get_called_class().": Executing Query as $query");

        $query = (string) new Postgres_Backtick_Filter($query);
        //\FB::info(get_called_class().": Executing Query as $query");

         try {

            $arr = $this->pgsql->prepare( $query );
            $arr->execute();

        } catch ( \PDOException $pdoe ){

            \FB::error(get_called_class().": Error executing query: ".$pdoe->getMessage() . " ($query)");

        }
        
        return $arr->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $str
     * @return string
     */
    public function execute_real_escape_string( $str ){

        return is_string($str) ? pg_escape_string($str): $str;
    }

}
