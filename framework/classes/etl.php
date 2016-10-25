<?php
/**
 * Created by JetBrains PhpStorm.
 * User: acer
 * Date: 21/08/13
 * Time: 10:16 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class etl {

    // ETL types
    const etl_eav_relational = 1;

    private $source,$destination = '';
    private $error = '';
    private $result = true;
    private $sConn,$dConn = null;   // source and destination connections
    private $simulate = false;      // simulate only?
    private $queries = array();     // holds all of the queries
    private $showsql = true;       // show SQL after running?
    private $entities = array();    // restrict entities if present
    private $mappings = array();    // mappings to target tables
    private $target_map = array();  // target map for lookups on mappings
    private $normalize_mappings = false;    // should we normalize to lowercase?

    private $etl_type = self::etl_eav_relational;
    private $etl_methods = array( self::etl_eav_relational=>'eav_to_relational');
    private $statistics = array();

    /**
     * @param array $options
     */
    public function __construct($options = array()){
        foreach ( $options as $member=>$value)
            if (property_exists(__CLASS__,$member))
            {
                $this->$member = in_array( $member,array('entities','mappings'))?
                    ($value?explode(',',$value):array()):$value;
            }
        // now adjust mappings
        if ( count($this->mappings)){
            $newmap = array();
            foreach ($this->mappings as $map){
                $tokens = explode('.',$map);
                $newmap[ $tokens[0] ] = $tokens[1];
            }
            $this->mappings = $newmap;
        }
    }
    public function go(){
        if ( ! $this->source ){
            $this->result = false;
            $this->error = "$this->source: No such source, or not defined";
            return false;
        }
        // set up source and destination connections
        $this->dConn = $this->get_connection($this->destination);
        $this->sConn = $this->get_connection($this->source);
        if ($this->sConn&&$this->dConn){
            $method = $this->etl_methods[$this->etl_type];
            $this->result = $this->$method($this->sConn,$this->dConn);
        } else return false;
        return false;
    }
    public function as_array(){
        return array(
            'class'=>'xobjects\etl',
            'statistics'=>$this->statistics,
            'result'=>$this->result?'success':'error',
            'error'=>$this->error,
            'simulate'=>$this->simulate?'yes':'no',
            'entities'=>implode(',',$this->entities),
            'queries'=>$this->queries,

        );
    }

    /**
     * Extract/Transform/Load from an Entity-Attribute-Value Model
     * to a Relational Model
     * @param \mysqli $source
     * @param \mysqli $destination
     * @return bool
     */
    private function eav_to_relational($source,$destination){
        $count_source = 0;
        $entities = array();
        $result = $source->query('SELECT * FROM `entities` WHERE TRUE');
        if ( ! $result){
            $this->error = $source->error;
            return false;
        }
        /**
         * Now go through and organize into entities
         */
        $entity = null;

        while ($row = $result->fetch_assoc()){
            $count_source++;
            /**
             * If this is our first entity, or a new one, we should save
             * the last one if present, and create a new one
             */
            if ( ! $entity || $entity['entity']!= $row['entity']){
                if ($entity) array_push($entities,$entity);
                $entity = array(
                    'entity'=>$row['entity']
                );
            }
            // merge in values intelligently
            $entity = $this->entity_merge($entity,$row);
        }
        /**
         * Now go through all the Entities and for each one,
         * load it to a new record
         */
        foreach( $entities as $entity){
            /**
             * If we're filtering, skip any entities whose type doesn't match the filter
             */
            if ( count($this->entities) && ! in_array($entity['entity_type'],$this->entities))
                continue;
            else {
                $res = $this->load_entity($entity,$destination);
                if ( ! $res) break; // break on error
            }
        }

        $this->statistics['source_rows'] = $count_source;
        $this->statistics['count_entities']= count($entities);
        return true;
    }

    /**
     * Perform an intelligent merge of entities, so as not to lose any
     * original values for duplicate fields
     * @param $entity
     * @param $row
     * @return mixed
     */
    private function entity_merge($entity,$row){
        foreach( $row as $name=>$value){
            switch( $name){
                case 'audit date':
                    $entity['created_date'] = $value;
                break;
                case 'entity':
                    // convert to id
                    $entity['id'] = $value;
                break;
                case 'id':
                    // no need for original db id
                break;
                case 'value':
                    // no need for this one either
                break;
                case 'increment':
                    // nor this one
                break;
                case 'key':
                    if ( $value == 'allownull')
                        $value = 'is_null_allowed';
                    if ( $value == 'option_value')
                        $value = 'value';
                    if ( $value == 'repeatable')
                        $value = 'is_repeatable';
                    if ( $value == 'mandatory')
                        $value = 'is_mandatory';
                    if ( $value == 'tag_answer')
                        $value = 'text';
                    if ( $value == 'project_user')
                        $value = 'user_id';

                    if ( $value == 'form_project')
                        $value = 'project_id';
                    // project fields
                    //if ( preg_match('/project_([a-z]+)/',$value,$hits))
                      //  $value = $hits[1]."_id";
                    // swap id for weird ones with id_ prefix
                    if ( preg_match('/id_([a-z|_]+)/',$value,$hits))
                        $value = $hits[1]."_id";
                    // normalize a few things
                    if (  ! in_array($value,array('data_type','user_name','first_name','last_name','entity_type')) && preg_match( '/([a-z]+)_(type|name|description|right|role)/',$value,$hits))
                        $value = $hits[2];
                    $entity[$value] = $this->adjust_for_mapping( $value, $row['value']);
                    //echo "Entity $value = ".$entity[$value]."\r\n";
                break;
                default:
                        $entity[$name] = $value;
                break;
            }
        }
        return $entity;
    }

    /**
     * Adjust a value based on any existing target mappings
     * @param string $name of field
     * @param mixed $value to set
     * @return mixed adjusted value
     */
    private function adjust_for_mapping($name,$value){
        static $keys = null;
        if ( ! $keys ) $keys = array_keys($this->mappings);
        if ( in_array($name,$keys)){
            $value = $this->get_mapped_value($this->mappings[$name],$value);
        }
        return $value;
    }

    /**
     * Get the mapped value for a given name value pair
     * @param $map_name
     * @param $value
     * @return string
     */
    private function get_mapped_value($map_name,$value){
        if ($this->normalize_mappings)
            $value = strtolower($value);
        return $value == 'varchar'?5:$value;
    }

    /**
     * Get a database connection to a named database
     * @param $database_name
     * @return \mysqli|null
     */
    private function get_connection($database_name){
        $conn = @new \mysqli(
            'localhost',
            'root',
            '',
            $database_name
        );
        if ($conn->connect_error){
            $this->result = false;
            $this->error = $conn->connect_error;
            $conn = null;
        }
        return $conn;
    }

    /**
     * Load a packaged Entity array into the Target (destination) Schema.
     * @param array $entity
     * @param \mysqli $destination
     * @return bool true if successful
     */
    private function load_entity($entity,$destination){
        $this->statistics['skipped_entities'] = 0;
        static $tables = array();
        if ( count($this->entities) && ! in_array($this->target_table($entity),$this->entities)){
            $this->statistics['skipped_entities']++;    // skip it
        } else {
            if ( $this->target_exists($entity,$destination)){
                $sql = "UPDATE `".$this->target_table($entity)."` SET ".
                    $this->set_clause($entity). " WHERE `id` = ". $entity['id'];
            } else {
                $columns = $this->sql_str($entity,'columns');
                $values = $this->sql_str($entity,'values');
                $sql = "INSERT INTO `".$this->target_table($entity)."` ($columns) VALUES($values)";
            }
            if ($this->showsql) $this->queries[] = $sql . "<br><br>Original Entity: ". new \xo_array($entity);
            // now insert it
            if ( ! $this->simulate ){
                $result = $destination->query($sql);
                if ( ! $result ){
                    if ( ! preg_match('/Duplicate entry/',$destination->error)){
                        $this->result = false;
                        $this->error = $destination->error. "($sql)";
                        return false;
                    }
                }
            }
        }
        return true;

    }

    /**
     * Does this entity already exist in the target (destination) database?
     * @param array $entity to check
     * @param \mysqli $destination connection
     * @return bool true if it already exists
     */
    private function target_exists($entity,$destination){
        $exists = false;
        $sql = "SELECT count(`id`) as `exists` FROM `".$entity['entity_type']."` WHERE `id` = ".$entity['id']." ";
        $result = $destination->query($sql);
        if ( $result) {
            $row = $result->fetch_assoc();
            $exists = (int)$row['exists']>0;
        }
        return $exists;
    }

    /**
     * Get the SET values clause for a given entity
     * @param array $entity
     * @return string the clause
     */
    private function set_clause($entity){
        $str = '';
        foreach ($entity as $name=>$value){
            if ( ! in_array($name,array('entity','entity_type','increment'))){
                $token = "`$name` = '".$this->dConn->real_escape_string(stripslashes($value))."'";
                $str .= $str?",$token":$token;
            }
        }
        return $str;
    }


    /**
     * Get the columns or vaues string for a given entity
     * @param array $entity
     * @param string $type
     * @return string
     */
    private function sql_str($entity,$type){
        $str = '';
        foreach ($entity as $name=>$value){
            if ( ! in_array($name,array('entity','entity_type','increment'))){
                $token = $type=='columns'?"`$name`":"'".$this->dConn->real_escape_string(stripslashes($value))."'";
                $str .= $str?",$token":$token;
            }
        }
        return $str;
    }

    /**
     * Get the target table for a given Entity
     * @param array $entity
     * @return string
     */
    private function target_table($entity){
        $target = $entity['entity_type'];
        $target = ($target=='assignedproject')?'project_user':$target;
        return $target;
    }
}
