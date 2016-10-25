<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/13/15
 * Time: 11:17 AM
 */

namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Result\Fetch_Associative_Values;
use Code_Alchemy\Database\Result\Query_Result;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Models\Model_Configuration;

/**
 * Class Duplicate_Models
 * @package Code_Alchemy\Models\Factories
 *
 * Gets an array of Duplicate Models, as indicated by one or more fields
 *
 * Note: for performance, always limited to 100 results at a time
 */
class Duplicate_Models extends Array_Representable_Object{

    /**
     * Merge strategy: merge duplicates into the first model of the set
     */
    const MERGE_INTO_FIRST_MODEL = 1;

    /**
     * @var array of fields that indicate a duplicate
     */
    private $duplicate_fields = array();

    /**
     * @var string error exposed to caller
     */
    public $error = '';

    /**
     * @var string Updateable Model
     */
    private $updateable_model = '';

    /**
     * @var bool true to send output to firebug
     */
    private $firebug = false;

    /**
     * @var string Model Name
     */
    private $model_name = '';

    /**
     * @var int Page number
     */
    private $page_number = 1;

    /**
     * @var int Size of chunks to fetch
     */
    private $chunk_size = 10000;

    /**
     * @var bool true to simulate actions only
     */
    private $simulate = false;

    /**
     * @var int
     */
    private $offset = 0;


    /**
     * @param array $options
     */
    public function __construct( array $options ){

        // Set Options
        foreach ( $options as $member => $value )

            if ( property_exists($this,$member) )

                $this->$member = $value;

        $this->fetch_models();


    }

    /**
     * Fetch Models from database
     */
    private function fetch_models( ){

        $model_config = (new Model_Configuration())->model_for($this->model_name);

        $id_field = $model_config['key_column'];

        $table_name = $model_config['table_name'];

        $join_clause = $this->join_clause( $this->duplicate_fields);

        $offset = $this->offset ?

            $this->offset :
            $this->page_number * $this->chunk_size - $this->chunk_size;


        $SQL = "SELECT DISTINCT a.* FROM `$table_name` a INNER JOIN `$table_name` b ON $join_clause WHERE a.$id_field <> b.$id_field LIMIT $this->chunk_size OFFSET $offset";

        if ( $this->firebug ) \FB::info(get_called_class().": Fetch SQL is $SQL");

        if ( $db_result = (new Database())->query( $SQL ) ){

            // Fetch rows
            while ( $row = (new Fetch_Associative_Values(new Query_Result($db_result)))->as_array() )

                $this->array_values[] = (new Model($this->model_name))->seed_from($row);


            if ( $this->firebug ) \FB::info(get_called_class().": Found " . count( $this->array_values)." Models");

        }

    }

    /**
     * Gets Join clause from fields
     * @param array $fields
     * @return string
     */
    private function join_clause( array $fields ){

        $clause = '';

        foreach ( $fields as $field )

            $clause .=  $clause ? " AND (a.`$field` = b.`$field` OR (a.`$field` IS NULL AND b.`$field` IS NULL)) ": " (a.`$field` = b.`$field` OR (a.`$field` IS NULL AND b.`$field` IS NULL)) ";

        return $clause;
    }


    /**
     * Merge all duplicates in the set
     * @param callable $pre_merge_activities for user-defined actions
     * @param int $merge_strategy to use while merging
     * @return int number of records merged
     */
    public function merge( callable $pre_merge_activities, $merge_strategy = self::MERGE_INTO_FIRST_MODEL ){

        $num_merged = 0;

        // For each bucket of duplicates
        foreach ( $this->buckets_method_two() as $bucket ){

            if ( $this->firebug ) \FB::info(get_called_class().": ======= BEGIN BUCKET =======");

            // If pre-merge successful
            if ( $pre_merge_activities( $bucket, $this->error ) )

                $num_merged += $this->dedupe( $bucket, $merge_strategy );

            if ( $this->firebug ) \FB::info(get_called_class().": ======= END   BUCKET =======");
        }

        return $num_merged;

    }

    private function dedupe( array $bucket, $merge_strategy = self::MERGE_INTO_FIRST_MODEL ){

        $num_deduped = 0;

        // use strategy
        switch ( $merge_strategy ){

            case self::MERGE_INTO_FIRST_MODEL:

                $count = count($bucket);

                $keeper = array_shift($bucket);

                if ( $this->firebug ) \FB::info(get_called_class()." Keeper has id $keeper->id");

                if ( count( $bucket) < $count ){

                    foreach ( $bucket as $model ){

                        if ( $this->updateable_model ){

                            $model = (new Model($this->updateable_model))

                                ->find(new Key_Column($this->updateable_model)."='".$model->id."'");

                        }

                        $reference_column = @(new Model_Configuration())->model_for($this->updateable_model)['reference_column'];

                        if ( $this->firebug ) \FB::info(get_called_class().": Model ".$model->$reference_column ." ($model->id) is a Duplicate and will be deleted");


                        if ( ! $this->simulate )

                            if ( $model->delete(true))

                                $num_deduped++;

                            else $this->error = $model->error();

                    }

                }
        }

        return $num_deduped;

    }

    /**
     * @return array of Buckets, using second method
     */
    public function buckets_method_two( $as_array = false ){

        $buckets = array();

        foreach ( $this->array_values as $model ){

            // Get bucket signature
            $signature = md5( $this->concatenated_duplicate_fields( $model ));

            // Not set yet?
            if ( ! isset( $buckets[ $signature ]) )

                $buckets[ $signature ] = array();

            // Add to bucket
            $buckets[ $signature ][] = $as_array ? $model->as_array(): $model;
        }

        return $buckets;
    }

    /**
     * @param Model $model
     * @return string value
     */
    private function concatenated_duplicate_fields( Model $model ){

        $value = '';

        foreach ( $this->duplicate_fields as $field )

            $value .= ($value?'-':'').$model->$field;

        return $value;

    }

    /**
     * @return array of Buckets
     */
    public function buckets( $as_array = false ){

        $buckets = array();

        $current_bucket = array();

        $last_model = null;

        foreach ( $this->array_values as $model ){

            if ( $this->firebug ) \FB::info(get_called_class().": Analyzing Model $model->id for Buckets");

            if ( is_object($model) ) {

                // No last model yet?
                if ( ! $last_model ) {

                    $current_bucket[ $model->id ] = $as_array ? $model->as_array(): $model;

                    $last_model = $model;
                }

                else {

                    // Last matches this one
                    if ( $this->is_duplicate( $model, $last_model) )

                        $current_bucket[ $model->id ] = $as_array ? $model->as_array() : $model;

                    else {

                        // restart a new bucket
                        $buckets[] = $current_bucket;

                        $current_bucket = array();

                        $last_model = null;
                    }

                    // Save Model
                    $last_model = $model;
                }
            }


        }

        // Fetch leftover bucket
        if ( count( $current_bucket ) > 0 ) $buckets[] = $current_bucket;

        if ( $this->firebug ) {

            \FB::info(get_called_class().": Buckets appear next");

            \FB::info($buckets);
        }

        return $buckets;
    }

    /**
     * @param Model $a
     * @param Model $b
     * @return bool true if Models are duplicates
     */
    private function is_duplicate( Model $a, Model $b ){

        $is_duplicate = true;

        foreach( $this->duplicate_fields as $field )

            if ( $a->$field != $b->$field ){

                $is_duplicate = false;

                if ( $this->firebug ) \FB::info(get_called_class(). $a->$field ." != ".$b->$field);

                break;
            }

        if ( $this->firebug ) \FB::info(get_called_class().": Model $a->id ".($is_duplicate? ' IS ': ' IS NOT ')." a Duplicate of $b->id");
        return $is_duplicate;
    }
}