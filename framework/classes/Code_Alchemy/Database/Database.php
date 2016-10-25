<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 29/09/14
 * Time: 05:10 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Database;


use Code_Alchemy\Applications\Toolboxes\Command_Line;
use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Database\Handlers\Alternate_Database_Handler;
use Code_Alchemy\Database\Helpers\Extract_Table_Name_from_SQL_Query;
use Code_Alchemy\Database\SQL\Fetch_SQL;
use Code_Alchemy\Database\SQL\Find_SQL;
use Code_Alchemy\Database\SQL\Insert_SQL;
use Code_Alchemy\Database\Table\Table_Column_Names;
use Code_Alchemy\Models\Helpers\Key_Column_For;
use Code_Alchemy\Models\Model_Settings;

/**
 * Class Database
 * @package Code_Alchemy\Database
 *
 *
 * @method tables_and_views( bool $ignore_cache = false )
 */
class Database extends Alchemist {

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
    public function __construct( \stdClass $database = null, $verbose = false ){

        // Default
        $this->db_type = "pgsql";

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();
        
        $this->file_path = $webapp_location.'/app/config/models.json';
            
        // Initialize Cache
        if ( ! self::$cache ) self::$cache = new Fast_Cache(100);

        // Handle alternate DB case
        if ( ! $database ) $database = (new Alternate_Database_Handler())->db();

        // If not set locally
        if ( ! $database )

            // if set in configuration
        {

            $database_Configuration_File = (new Database_Configuration_File());

            if ( $database_Configuration_File->is_configured() ){

                // use connection configured
                $this->connections = $database_Configuration_File->is_configured();

                // Only if an array
                if ( is_array($this->connections) && ! isset($this->connections['database'])){

                    foreach($this->connections as $key => $conn):
                        if( is_array( $conn) && isset( $conn['is_main'])):
                            // cache name
                            $database = (object) $conn;
                            $this->db_type = (string)$key ;
                            $this->name = (string)$conn['database'];

                            // Allow for debugging
                            $this->debug = isset($conn['debug']) && $conn['debug'];

                            break;
                        endif;
                    endforeach;

                } else {

                    $database = $this->connections;

                    $this->name = $this->connections['database'];
                }

            }
            
            

            $classname = "\\Code_Alchemy\\Database\\Adapters\\Adapter_$this->db_type";


            $this->adapter = new $classname($database, $this->name, $this->debug );

        } else {

            $this->db_type = $database->db_type;

            $classname = "\\Code_Alchemy\\Database\\Adapters\\Adapter_$this->db_type";

            $this->adapter = new $classname($database, $this->name, $this->debug);

            $this->name = $database->database;
        }

       // if ( $this->debug)  \FB::info(get_called_class().": Database name is $this->name");
    }
    
    
    
    /**
     * @return result
     */
    public function query($query, &$is_error = false,$debug = false){

        $this->debug = $debug;

        //serach name table
        $_query = str_replace(array("`",chr(96)),array('',''), $query);

        $this->table_search = (string) new Extract_Table_Name_from_SQL_Query($_query);

        if ( ! $this->table_search ){

            //\FB::error(get_called_class().": $query: table not found");

            //return null;

        }


        //load models.json
        if ( ! file_exists($this->file_path)){

            \FB::error(get_called_class().": $this->file_path: No such file or directory");

            return;

        }
        //extract json data
        $contents = @file_get_contents($this->file_path);

        $data = json_decode($contents,true);
        




        //filter object table with table that execute
        if(isset($data['models'][$this->table_search]['connection'])):
            $type = $data['models'][$this->table_search]['connection'];
            $classname = "\\Code_Alchemy\\Database\\Adapters\\Adapter_$type";

            $altAdapter = new $classname((object)$this->connections[$type], $this->connections[$type]['database']);

            $execute = $altAdapter->execute($query, $is_error);

            if ( $this->debug ) {

                \FB::info(get_called_class().": Execute result appears next");

                \FB::info($execute);
            }

            if ( $is_error ){

                $this->error = $altAdapter->error();


            }
            return $execute;

        else:
            $execute1 = $this->adapter->execute($query, $is_error);

            //$this->afterExecuteHook( $execute1 );

            if ( $is_error ){

                $this->error = $this->adapter->error();

            }

            return $execute1;
        endif;

    }

    /**
     * After Execute Hook
     * @param \PDOStatement $statement
     */
    private function afterExecuteHook( \PDOStatement $statement ){

        /*
        $statement->closeCursor();

        $statement = null;
*/
    }

    /**
     * @return execute result
     */
    public function real_escape_string($str, $table){

         //load models.json
        if ( ! file_exists($this->file_path)){

            \FB::error(get_called_class().": $this->file_path: No such file or directory");

            return;

        }
        //extract json data
        $contents = @file_get_contents($this->file_path);

        $data = json_decode($contents,true);


        if(isset($data['models'][$table]['connection'])):
            $type = $data['models'][$table]['connection'];
            $classname = "\\Code_Alchemy\\Database\\Adapters\\Adapter_$type";
        
            $altAdapter = new $classname((object)$this->connections[$type], $this->connections[$type]['database']);
        
            return $altAdapter->execute_real_escape_string($str);
        
        else:
            return $this->adapter->execute_real_escape_string($str);
        endif;

    }
    /**
     * @return execute result
     */
    public function save_to_table( $table_name, $values ){

        //load models.json
        if ( ! file_exists($this->file_path)){

            \FB::error(get_called_class().": $this->file_path: No such file or directory");

            return;

        }
        //extract json data
        $contents = @file_get_contents($this->file_path);

        $data = json_decode($contents,true);


        if(isset($data['models'][$table_name]['connection'])):
            $type = $data['models'][$table_name]['connection'];
            $classname = "\\Code_Alchemy\\Database\\Adapters\\Adapter_$type";

            $altAdapter = new $classname((object)$this->connections[$type], $this->connections[$type]['database']);

            $execute_save_to_table = $altAdapter->execute_save_to_table($table_name, $values);

            $this->error = $altAdapter->error();

            return $execute_save_to_table;

        else:

            $execute_save_to_table1 = $this->adapter->execute_save_to_table($table_name, $values);

            $this->error = $this->adapter->error();

            return $execute_save_to_table1;
        endif;
        
    }
    
    /**
     * @return array result
     */
    public function get_field_names($table_name){

        //if ( $this->is_development()) \FB::info(get_called_class().": Getting field names for $table_name");

         //load models.json
        if ( ! file_exists($this->file_path)){

            \FB::error(get_called_class().": $this->file_path: No such file or directory");

            return array();

        }
        //extract json data
        $contents = @file_get_contents($this->file_path);

        $data = json_decode($contents,true);


        if(isset($data['models'][$table_name]['connection'])):
            $type = $data['models'][$table_name]['connection'];
            $classname = "\\Code_Alchemy\\Database\\Adapters\\Adapter_$type";

            $altAdapter = new $classname((object)$this->connections[$type], $this->connections[$type]['database']);

            return $altAdapter->execute_get_field_names($table_name);

        else:
            return $this->adapter->execute_get_field_names($table_name);
        endif;

    }



    public function load_from_table(
        $table_name,
        $query,
        $comma_substitute = '',
        $debug = false

    ){

        //load models.json
        if ( ! file_exists($this->file_path)){

            \FB::error(get_called_class().": $this->file_path: No such file or directory");

            return;

        }
        //extract json data
        $contents = @file_get_contents($this->file_path);

        $data = json_decode($contents,true);


        //filter object table with table that execute
        if(isset($data['models'][$table_name]['connection'])):
            $type = $data['models'][$table_name]['connection'];
            $classname = "\\Code_Alchemy\\Database\\Adapters\\Adapter_$type";

            $altAdapter = new $classname((object)$this->connections[$type], $this->connections[$type]['database']);

            //\FB::info("query in DATABASE.php:");
            //\FB::info($query);

            return $altAdapter->execute_from_table(
                $table_name,
                $query,
                $comma_substitute,
                $debug = false

            );

        else:
            return $this->adapter->execute_from_table(
                $table_name,
                $query,
                $comma_substitute,
                $debug = false

            );
        endif;

    }




    public function __call( $method_name, $arguments ){

        $adaptor = $this->adaptor( $this->adapter );

        if ( method_exists( $adaptor, $method_name) ){

            $return_value = call_user_func_array(array($adaptor, $method_name), $arguments);

            if ( $adaptor->error() ) {

                \FB::error(get_called_class().": Adaptor error".$adaptor->error());

                $this->error = $adaptor->error();
            }


            return $return_value;

        }


        else {

            $this->error = "$method_name: No such method";

            return false;
        }

    }

    /**
     * @return string last error
     */
    public function error(){ return isset($this->error)? $this->error :''; }




    /**
     * @param string $name of Table
     * @return bool true if table exists in DB
     */
    public function has_table( $name ){

        static $tables = null;

        if ( ! $tables ) $tables = $this->tables_and_views();

        $table_exists = !!($name && (array_search($name, $tables) > -1));


        return $table_exists;

    }

    /**
     * @return string name of database
     */
    public function name(){

        return (string) $this->name;

    }



    /**
     * @param string $name of table to create
     * @param string $template to use
     */
    public function create_table( $name, $template = 'base' ){

        // Cheat!  Get the installer
        $installer = new Command_Line(array());

        $installer->create_database_table($name,"$template");

    }

    /**
     * @param $adapter
     * @return Adapter_mysql
*/
    public function adaptor( $adapter ){

        return $adapter;
    }



}
