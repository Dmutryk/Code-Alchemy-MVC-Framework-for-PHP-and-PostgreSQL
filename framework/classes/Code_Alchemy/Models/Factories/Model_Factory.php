<?php
/**
 * Project:     X-Objects MVC for PHP
 * Module:      Models and Data Representation
 * Component:   xo_model: Model for Data
 *
 * Description: Encapsulation of Model class, that allows us to transfer
 *              data from the persistence layer to an object representation
 *              quickly, and vice versa
 */

namespace Code_Alchemy\Models\Factories;

use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\components\seo_name;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Creators\Database_Table_Creator;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Postgres\Is_Postgres_Model;
use Code_Alchemy\Database\Result\Fetch_Associative_Values;
use Code_Alchemy\Database\Result\Query_Result;
use Code_Alchemy\Database\SQL\SQLCreator;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Factories\Helpers\Model_Count;
use Code_Alchemy\Models\Helpers\Dynamic_Model_Fields_Fetcher;
use Code_Alchemy\Models\Helpers\Intersections_For;
use Code_Alchemy\Models\Helpers\Key_Column_For;
use Code_Alchemy\Models\Helpers\Model_Class_For;
use Code_Alchemy\Models\Helpers\Model_Class_Verifier;
use Code_Alchemy\Models\Helpers\Reference_Column_For;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Models\Model_Configuration;
use Code_Alchemy\Security\Officer;

class Model_Factory extends Alchemist {

    /**
     * @var bool true to enable debugging
     */
    private $debug = false;


    public $last_error = '';            // string explaining the last error that occurred

    /**
     * @var string Model Key
     */
    private $key = '';

    /**
     * @var Fast_Cache for performance
     */
    private static $cache;


    public function __construct($key){

        // Initialize Cache if necessary
        if ( ! self::$cache ) self::$cache = new Fast_Cache();

        $this->key = $key;


	}

    /**
     * @param $query
     * @return Model
     */
    public function find_one( $query ){

        return (new Model($this->key))->find($query);

    }

    /**
     * @param $query
     * @return array
     */
    public function find_one_as_array( $query ){

        return $this->find_one($query)->as_array();

    }


    /**
     * Synonym to Find One as Array
     * @param $query
     * @return array
     */
    public function one_as_array( $query ){

        return $this->find_one_as_array($query);

    }

    /**
     * Synonym to Find One as Array
     * @param $query
     * @return Array_Object
     */
    public function one_as_array_object( $query ){

        return new Array_Object($this->find_one_as_array($query));

    }


    /**
     * Find a related record
     * @param $model_name
     * @param $called_class
     * @param $key
     * @param null $logger
     * @return mixed
     */
    public function find_related(
        $model_name,
        $called_class,
        $key,
        $logger = null
    ){

        $debug = false;

        $parts = explode( '\\' , $called_class);

        $length = count( $parts );

        $class_name = $length? $parts[$length-1]:$called_class;

        $class = $length? (string) new \Code_Alchemy\components\namespace_parser($called_class)."\\$model_name":$model_name;

        $foreign_key = $class_name."_id";

        $source = $class::source();

        $safe_delete = $source->supports_safe_delete();

        $key_col = $source->keycol();

        $query = $safe_delete? "is_deleted='0',$foreign_key='" . $key . "'":"$foreign_key='" . $key . "'";

        if ( $logger )
            $logger->log("xo_model::find_related(): model_name=$model_name,called_class=$called_class,key=$key,foreign_key=$foreign_key,key_col=$key_col,query=$query",3,
            new xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__));

        return $class::create( $query );

    }

    public function collection($query = null){

    }


    /**
     * Create a new Object from an Associative Array, saving it
     * @param $array array of member values
     * @param $logger object that allows X-Objects to post any log messages when
     *  an error occurs
     * @return mixed the created object
     */
    public function create_from_associative($array,$logger=null){

        if (! is_array($array)){

            trigger_error("Argument must be an associative array",E_USER_ERROR);

            return null;
        }


        $class = (string) new Model_Class_For($this->key);

        $is_dynamic_model = (new Model_Class_Verifier($class))->is_dynamic_model();

        $object = $is_dynamic_model ?

            (new Dynamic_Model($this->key))->create_from( $array):

                $class::create_from_associative($array);

        if ( ! $object || !$object->exists ){

            $this->last_error =  $is_dynamic_model? $object->error(): $class::$last_class_error;

        }

        return $object;
    }

    /**
     * Find the first Object matching a query
     * @param string $query the query to execute
     * @return object|null the resulting object if any
     */
    public function find_first($query="all", $firebug = false ){

        $model_class_name = (string)new Model_Class_For($this->key);

        return (new Model_Class_Verifier($model_class_name))->is_dynamic_model()?

            (new Dynamic_Model($this->key))->find($query) :

                new $model_class_name( $query );

    }

    /**
     * Find and return an Array of all undeleted Models matching the query
     * @param string $query
     * @param bool $associative
     * @return array
     */
    public function find_all_undeleted($query = 'all',$associative=true,$firebug=false){

        // Is deleted
        $is_deleted_clause = (new Model_Configuration())

            ->model_for( $this->key )['safe_delete'] ?

                "is_deleted='0'," : '';


        return $this->find_all("$is_deleted_clause$query",$associative,$firebug);
    }

    /**
     * Find all Models
     * @param string $query
     * @param bool $associative
     * @param bool $firebug
     * @param array $select_columns
     * @return array
     * @throws \Exception
     */
    public function find_all(

        $query = "all",

        $associative = true,

        $firebug = false,

        array $select_columns = []
){

        // Construct Cache Key
        $cache_key_builder = $this->key . "-" . $query;

        $cache_key = (string) new Cache_Key(md5($cache_key_builder));

        // In Cache already?
        if ( self::$cache->exists($cache_key)){

            return self::$cache->get($cache_key);

        }


        $tag = new \xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        if ($firebug )

            \FB::info(get_called_class().": Finding all Models of type $this->key using query=$query");

        $class = (string) new Model_Class_For( $this->key );

        $is_dynamic_model = (new Model_Class_Verifier($class))->is_dynamic_model();

        $sample = $is_dynamic_model ?

            new $class( $this->key ) :

                new $class;

        $objects = array();

        $conditions = \HumanLanguageQuery::create($query)->conditions();

        if ( $firebug )

            \FB::log("$tag->firebug_format: Conditions are $conditions");

        $use_alternate = $is_dynamic_model ? false: $sample->source()->use_alternate();

        $table_name = (new Model_Configuration())->model_for($this->key)['table_name'];

        $columns =  count($select_columns)? implode(',',$select_columns): "*";

        $sql = "SELECT $columns FROM `". $table_name ."` ".

            SQLCreator::getWHEREClause( \HumanLanguageQuery::create($query)->conditions());


        if ( $firebug)

            \FB::log("$tag->firebug_format: Key is $this->key SQL = $sql");

        $database = new Database();


        $db_result = $database->query($sql);

        if ( $db_result){

            if ( $firebug ) {


                \FB::info($db_result);


            }

            $keycol = (new Dynamic_Model($this->key))->key_column();

            // Is this pg connection
            if ( (new Is_Postgres_Model($this->key))->bool_value() ){

                //\FB::info(get_called_class().": $this->key: This is a postgres model");

                $all_rows = (new Fetch_Associative_Values(new Query_Result($db_result)))->as_array();

                // Go through the rows
                foreach ( $all_rows as $row )

                    $objects[$row[$keycol]] = new Model($this->key,$row);

            } else

            while (

//            ($row = ($associative?$db_result->fetch_assoc():$db_result->fetch_row()))

                ($row = ($associative?

                    (new Fetch_Associative_Values(new Query_Result($db_result)))->as_array():$db_result->fetch_row()))

                != false){

                $key = $associative?$row[$keycol]:null;

                if ( $firebug ) {

                    \FB::info(get_called_class().": Found a row! Key is $key, row appears next");

                    \FB::info($row);
                }

                // bootstrap it using the fetched array

                $class = (string) new Model_Class_For($this->key);

                $objects[$key] = $is_dynamic_model ?

                    new $class($this->key,$row):

                        new $class($row);

            }

            //$this->afterQueryHook( $db_result );

        } else {

            \FB::error("A SQL or database error occurred: ".$database->error(). " for query $sql");
        }

        if ($firebug) \FB::log("$tag->firebug_format: Done with last database operation");

        // Add tocache
        self::$cache->set($cache_key,$objects);

        return $objects;
	}

    /**
     * Clean up after Statement Query
     * @param \PDOStatement $statement
     */
    private function afterQueryHook( \PDOStatement $statement ){

        $statement->closeCursor();

        $statement = null;

    }

    public function find_all_assoc($query = "all"){
        return object_factory::create_assoc($this->key,null,null,null,null,$query);
    }

    public function fetch_as_bundle($query="all"){
        $bundle = new xo_object_bundle();
        $bundle->objects = $this->find_all($query);
        return $bundle;
    }
	public function count_all( $query = "all"){

        $int_value = (new Model_Count($this->key, $query))->int_value();

        return $int_value;

	}

    /**
     * COunt all undeleted records
     * @param string $query
     * @return int Count
     */
    public function count_all_undeleted( $query = 'all' ){

        if ( $this->supports_soft_delete())

            $query = (strlen($query))? "$query,is_deleted='0'":"is_deleted='0'";

        return $this->count_all($query);

    }

	public function has_field($f){
		$o = new $this->key;
		return in_array( $f, $o->source()->columns());
	}


    /**
     * @param $model
     * @return business_object
     */
    private function model( $model ){

        return $model;
    }

    /**
     * Delete all models matching a query
     * @param string $query
     */
    public function delete_all( $query = "all" ){

        foreach ( $this->find_all($query) as $model )

            $this->model( $model )->delete();

    }

    /**
     * @param array $ids
     */
    public function delete_all_with_ids( array $ids ){

        foreach ( $ids as $id )

            (new Model($this->key))->find(new Key_Column($this->key)."='$id'")

                ->delete();
    }

    /**
     * Fetch all undeleted Models as an array of arrays
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array of model arrays
     */
    public function fetch_all_undeleted_as_array( $query = 'all', $firebug = false, $exclusions = array()){

        $query1 = $query;

        if ( $this->supports_soft_delete())

            $query1 = strlen($query) ? "$query,is_deleted='0'":"is_deleted='0'";

        return $this->fetch_all_as_array($query1,$firebug,$exclusions);

    }

    /**
     * Fetch all undeleted, sorted Models as an array of arrays
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array of model arrays
     */
    public function fetch_all_undeleted_sorted_as_array( $query = 'all', $firebug = false, $exclusions = array()){

        $query1 = strlen($query) ? "$query,order by sortable_id ASC":"order by sortable_id ASC";

        $firebug = $firebug?$firebug:$this->debug;

        return $this->fetch_all_undeleted_as_array($query1,$firebug,$exclusions);

    }



    /**
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array
     */
    public function all_undeleted_sorted_as_associative_array($query = 'all', $firebug = false,$exclusions = array()){

        $array = array();

        $reference_column = $this->reference_column();

        foreach ( $this->all_undeleted_sorted_as_array($query,$firebug,$exclusions) as $model )

            $array[((string) new seo_name($model[$reference_column]))] = $model;

        return $array;

    }

    /**
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array
     */
    public function all_undeleted_sorted_as_array( $query = 'all', $firebug = false,$exclusions = array()){

        return $this->fetch_all_undeleted_sorted_as_array($query,$firebug,$exclusions);
    }

    /**
     * Fetch all elements, sorted, as an array
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array
     */
    public function all_sorted_as_array( $query = 'all', $firebug = false,$exclusions = array()){

        $query = $query ? "$query,order by sortable_id ASC": "order by sortable_id ASC";

        return $this->fetch_all_as_array($query,$firebug,$exclusions);

    }

    /**
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array
     */
    public function all_undeleted_sorted_as_object( $query = 'all', $firebug = false,$exclusions = array()){

        $models = array();

        foreach ( $this->fetch_all_undeleted_sorted_as_array($query,$firebug,$exclusions) as $model )

            $models[] = new Array_Object( $model );

        return $models;
    }




    /**
     * @return bool true if soft delete is supported
     */
    public function supports_soft_delete(){

        return !! $this->is_safe_deleteable();
    }

    /**
     * Fetch all Models as arrays, grouped by a Column
     * @param $grouping_column_name
     * @param $query
     * @param bool $firebug
     * @param array $exclusions
     * @param bool $is_associative
     * @return array
     */
    public function fetch_as_array_with_grouping(
        $grouping_column_name,
        $query,
        $firebug = false,
        $exclusions = array(),
        $is_associative = false

    ){

        $models = ! $is_associative ?

            $this->fetch_all_as_array($query,$firebug,$exclusions) :

            $this->all_undeleted_sorted_as_associative_array($query,$firebug,$exclusions)
        ;

        if ( count( $models) ){

            $groups = [];

            foreach ( $models as $model ){

                $group_id = $model[$grouping_column_name];

                if ( ! isset($groups[$group_id ]))

                    $groups[ $group_id ] = [

                        'group_id' => $group_id,

                        'models' => []
                    ];

                $key_lookup = (string) new Reference_Column_For($this->key);

                $key = $is_associative ? (string) new seo_name($model[$key_lookup]): null;

                if ( $is_associative)

                    $groups[ $group_id]['models'][ $key ] = $model;

                else

                    $groups[ $group_id]['models'][] = $model;



            }
        }

        return isset( $groups )? $groups: $models;

    }

    /**
     * Fetch all of the specified Models, returning an array of members
     * @param string $query to search
     * @param bool $firebug
     * @param array $exclusions for each member
     * @return array of arrays!
     */
    public function fetch_all_as_array(
        $query="all",
        $firebug = false,
        $exclusions = array()
    ){

        $tag = new \Code_Alchemy\tools\code_tag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        $os = $this->find_all($query,true,$firebug);

        $a = array();

        if ( $os )

            foreach( $os as $o){

                array_push( $a, $this->model($o)->as_array($exclusions));

            }


        return $a;

    }

    /**
     * @param array $ids
     * @return array of Models
     */
    public function fetch_all_as_array_from_ids( array $ids ){

        $models = array();

        foreach ( $this->fetch_all_from_ids( $ids ) as $model )

            $models[] = $model->as_array();

        return $models;
    }

    /**
     * Fetch a bunch of Models, given an array of unique database Ids
     * @param array $ids of objects to fetch
     * @return array of Models from given Ids
     */
    public function fetch_all_from_ids( array $ids ){

        $models = [];

        foreach ( $ids as $id ){

            $model = (new Model( $this->key ))

                ->find( new Key_Column_For($this->key)."='$id'");

            if ( $model->exists )

                $models[] = $model;
        }

        return $models;

    }

    /**
     * @param $json object representation of values to update
     * @param $keys array keys indicating search for update
     * @param $map array map of json members to db fields
     * @return bool true if operation successful
     * errors logged in class's last class error
     */
    public function upsert_from_json($json,$keys,$map = array()){
        $class = $this->key;
        $conditions = "";
        foreach ( $keys as $field=>$member)
            $conditions .= ($conditions)?",$field='".$json->$member."'":"$field='".$json->$member."'";
        $object = new $class($conditions);
        foreach ( $json as $member=>$value){
            $mem = isset($map[$member])?$map[$member]:$member;
            $object->$mem = $value;
        }
        $result = $object->save();
        $class::$last_class_error = $object->save_error;
        return $result;
    }

    /**
     * Find all instances of a specific column (field) for given Model
     * and return them as an array
     * @param string $member the member name to fetch for each row
     * @param string $search an optional limiter for search
     * @param bool $compact_array optional to compact the array after fetching
     * @return array the results
     */
    public function find_all_values_as_array(
        $member,
        $search="all",
        $compact_array = false,
        $debug = false
    ){

        if ( $debug ) \FB::info(get_called_class().": Search is $search");

        $values = array();

        $class = (string) new Model_Class_For($this->key);

        if ( class_exists( $class )){

            $is_dynamic_model = (new Model_Class_Verifier($class))->is_dynamic_model();

            $object = $is_dynamic_model ?

                new Dynamic_Model($this->key):

                $this->get_object(new $class);

            $table = $is_dynamic_model?

                $object->table_name():

                $object->get_source()->name();

            $query = "SELECT `$member` FROM `$table` ".SQLCreator::getWHEREclause(\HumanLanguageQuery::create($search)->conditions());

            if ( $debug ) \FB::info(get_called_class().": SQL Query is $query");

            $result = (new database())->query( $query );

            if ( $result){

                if ( get_class($result) == 'PDOStatement')

                    $values = $result->fetchAll(\PDO::FETCH_ASSOC);

                    else {

                        while ( $row = $result->fetch_assoc()){

                            if ( $debug )\FB::info($row);

                            array_push( $values ,$row[$member]);

                        }

                        $result->close();

                    }



            } else {

                if ( $debug ) \FB::warn(get_called_class().": No result from last query");
            }

            if ( $debug) \FB::info($values);

            if ( $compact_array)

                array_walk($values,function(&$item,$index){ $item = $item[0]; });


        }
        return $values;
    }

    /**
     * get all importable columns
     */
    public function importable_columns(){
        // first get all of them
        global $container;
        $class = $this->key;
        $source = $class::source();
        return $source->import_columns();
    }

    // get the key field for a given model
    public function key_field(){
        $object = new $this->key();
        return $object->source()->keycol();
    }
    // get the search field
    public function search_field(){
        $object = new $this->key();
        return $object->source()->search_field();
    }

    /**
     * Return an is_deleted clause, if supported, for this model
     * @param bool $is_deleted specifies the value of the clause result
     * @return string the clause, whcih could be empty
     */
    public function is_deleted_clause($is_deleted = false){
        $clause = '';
        $object = new $this->key();
        if ( in_array( 'is_deleted',$object->source()->columns())){
            //echo "model has is_deleted\r\n";
            $clause = "is_deleted='".($is_deleted?1:0)."'";

        }
        return $clause;
    }

    /**
     * Obtain the sum of a specific column on the Model, and using a specific Query
     * @param string $column name
     * @param string $query
     * @return float result
     */
    public function sum_of( $column , $query = 'all'){

        $conditions = HumanLanguageQuery::create($query)->conditions();


        $value = 0.0;

        $container = x_objects::instance();

        $sql = $container->services->mysql_service;

        $object = new $this->key();

        $result = $sql->query( "SELECT SUM(`".$column."`) as `x_sum` FROM ".$object->source()->name .    SQLCreator::getWHEREClause( HumanLanguageQuery::create($query)->conditions())

    );

        if ( $result ){

            $row = (new Fetch_Associative_Values(new Query_Result($result)))->as_array();

            $value = $row['x_sum'];
        }

        return $value;
    }

    /**
     * @return bool true if Model supports safe delete
     */
    public function is_safe_deleteable(){

        $configuration = (new Model_Configuration())->model_for($this->key);

        return !! (isset( $configuration['safe_delete']) && $configuration['safe_delete'] );

    }

    /**
     * @param \business_object $object
     * @return \business_object
     */
    private function get_object( \business_object $object ){ return $object; }

    /**
     * @return array of Fields associated with Model
     */
    public function fetch_fields(){

        $class = new Model_Class_For($this->key);

        // Get an empty object
        $is_dynamic_model = (new Model_Class_Verifier($class))->is_dynamic_model();

        $object = $is_dynamic_model ?

            new Dynamic_Model($this->key): new $this->key;

        // Get a fields fetcher
        $fetcher = $is_dynamic_model ?

            new Dynamic_Model_Fields_Fetcher( $object) :

                new \Code_Alchemy\helpers\model_fields_fetcher( $object );

        return $fetcher->as_array();
    }


    /**
     * @return array of Intersections for Model
     */
    public function intersections(){

        return (new Intersections_For($this->key))->as_array();

    }

    /**
     * @return array representation of one random undeleted record
     */
    public function fetch_one_random_undeleted_as_array( $query = null ){

        $one_random = $this->fetch_all_random_undeleted_as_array(1,$query);

        if ( count($one_random )) $one_random = $one_random[0];
        return $one_random;

    }

    /**
     * Fetch random undeleted records as arrays
     * @param int $count of records to fetch
     * @param string $query to apply when fetching
     * @return array of randomly selectged records
     */
    public function fetch_all_random_undeleted_as_array( $count, $query = null ){

        if ( $this->supports_soft_delete() ) $theQuery = $query ? "$query,is_deleted='0'":"is_deleted='0'";

        // If no records
        $count_all = $this->count_all($theQuery);

        if ( !$count_all )

            return array();

        $capacity = min($count_all,$count);

        $records = array();

        $ids = array();

        $table = array_pop(explode('\\',$this->key));

        $whereClause = (new SQLCreator())->getWHEREclause($theQuery);

        $q1 = "SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `$table` $whereClause";

        $db = new Database();

        $continue = true;

        while ( $continue && count($records)< $capacity){

            if ( $result = $db->query($q1)){

                $row = (new Fetch_Associative_Values(new Query_Result($result)))->as_array();

                $offset = $row['offset'];

                $ids_flat = implode(',',$ids);

                $condition = count($ids)> 0 ? " AND `id` NOT IN ($ids_flat)":'';

                $q2 = "SELECT * FROM `$table`  $whereClause $condition LIMIT $offset, 1 ";

                if ( $result2 = $db->query($q2)){

                    $row2 = (new Fetch_Associative_Values(new Query_Result($result2)))->as_array();

                    if ( $row2[(string) new Key_Column_For($table)]>0){

                        $object = (new Model($this->key))->seed_from($row2);

                        $records[] = $object->as_array();

                        $ids[] = $object->id;

                    }


                } else $continue = false;


            } else $continue = false;

        }

        return $records;


    }

    /**
     * @return string reference column
     */
    public function reference_column(){

        return @(new Model_Configuration())

            ->model_for($this->key)['reference_column'];


    }


    /**
     * @return array reference models
     */
    public function referenced_by(){

        $object = new $this->key;

        return $object->source()->referenced_by();

    }



    /**
     * @param $field_name
     * @return string lookup condition
     */
    public function lookup_condition_for( $field_name ){

        return '';

        /*
        $object = new $this->key;

        return $object->source()->lookup_condition_for( $field_name );
*/
    }

    /**
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array of my undeleted models
     */
    public function fetch_my_undeleted_as_array($query = 'all',$firebug = false,$exclusions = array()){

        $my_id = Officer::fetch()->me()->id;

        return $this->fetch_all_undeleted_as_array("user_id='$my_id',$query",$firebug,$exclusions);

    }


    /**
     * @param string $query
     * @param bool $firebug
     * @param array $exclusions
     * @return array of all undeleted records, as arrays
     */
    public function all_undeleted_as_array( $query = 'all', $firebug = false, $exclusions = array()){

        return $this->fetch_all_undeleted_as_array($query,$firebug,$exclusions);

    }

    /**
     * @param string $query
     * @param bool $associative
     * @param bool $firebug
     * @return array of all undeleted records
     */
    public function all_undeleted( $query = 'all', $associative = true, $firebug = false){

        return $this->find_all_undeleted($query,$associative,$firebug);

    }

    public function fetch_as_array( $query = '' ){

        $class = new Model_Class_For($this->key);

        // Get an empty object
        $is_dynamic_model = (new Model_Class_Verifier($class))->is_dynamic_model();

        $object = $is_dynamic_model ?

            new Dynamic_Model($this->key): new $this->key(

            );


    }

    /**
     * Invalidate the entire Cache
     */
    public static function invalidate_cache(){

        if ( self::$cache )  self::$cache->invalidate_cache();

    }

    /**
     * Select columns for a set of models
     * @param $columns
     * @param string $query
     * @param bool $associative
     * @param bool $firebug
     * @return array
     */
    public function select( $columns, $query = '', $associative = true, $firebug = false ){

        return $this->find_all($query,$associative,$firebug,$columns);

    }

    /**
     * @param $columns
     * @param string $query
     * @param bool $associative
     * @param bool $firebug
     * @return array
     */
    public function select_as_array( $columns, $query = '', $associative = true, $firebug = false ){

        $result = [];

        foreach ( $this->select($columns,$query,$associative,$firebug) as $index => $model )

            $result[$index] = $model->as_array();

        return $result;

    }

}

