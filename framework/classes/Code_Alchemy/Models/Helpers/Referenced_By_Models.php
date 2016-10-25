<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/23/15
 * Time: 3:21 PM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\helpers\plural_for;
use Code_Alchemy\Models\Components\Model_Settings;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Key_Column;

/**
 * Class Referenced_By_Models
 * @package Code_Alchemy\Models\Helpers
 * 
 * Gets Referenced by Models for a given Model, as an Array
 */
class Referenced_By_Models extends Array_Representable_Object{

    /**
     * @var bool true to debug component
     */
    private $debug = false;

    /**
     * @var
     */
    private static $cache = null;

    /**
     * Referenced_By_Models constructor.
     * @param string $model_name
     * @param Model_Settings $oModelSettings
     * @param array $aExistingMembers
     */
    public function __construct( $model_name, Model_Settings $oModelSettings, array $aExistingMembers ) { 

        //if ( $this->is_development()) \FB::info(get_called_class().": Pulling related Models for $model_name");
        // Initialize Cache if necessary
        if ( ! self::$cache)  self::$cache = new Fast_Cache();

        // for each referenced by
        foreach ( $oModelSettings->referenced_by as $sReferrer ){


            // Get referenced by settings
            $oReferencedBySettings =

                new Array_Object(

                    is_array(@$oModelSettings->referenced_by_settings[$sReferrer])

                        ?$oModelSettings->referenced_by_settings[$sReferrer]:array()
                );


            // Check if we should affix as associative
            $is_associative = ( is_array($oModelSettings->affix_as_associative))
                
                && in_array($sReferrer,$oModelSettings->affix_as_associative);

            // Referencing column
            $referencing_column =

                $oReferencedBySettings->referencing_column ?

                    $oReferencedBySettings->referencing_column:

                    $model_name.'_'.$oModelSettings->key_column;

            // Construct Query
            $referenced_value_key =

                $oReferencedBySettings->referencing_value_from ?

                    $oReferencedBySettings->referencing_value_from:

                    (string)new Key_Column($model_name);

            $referring_value = $aExistingMembers[$referenced_value_key];

            // Continue if no referencing value
            if ( ! $referring_value ){

                continue;

            }

            $query = $referencing_column . "='" .$referring_value . "'";

            // Get a condition if any
            if ( isset( $oModelSettings->affix_referenced_by_condition))

                $query .= ",".$oModelSettings->affix_referenced_by_condition;

            $model_Factory = (new Model_Factory($sReferrer));

            // Set method
            $method = $is_associative ? 'all_undeleted_sorted_as_associative_array':

                'all_undeleted_sorted_as_array';

            // Should we randomize?
            if ( $oModelSettings->randomize_affix_referenced_by )

                $method = $is_associative ? 'fetch_all_random_undeleted_as_associative_array':

                    'fetch_all_random_undeleted_as_array';


            // Get Models
            //$Models = $model_Factory->$method($query);
            $Models = $this->models($model_name,$sReferrer,$method,$query,$model_Factory);

            $keys = array_keys($Models);

            $label = (new plural_for($sReferrer))->word;

            // Only one?
            if ( count( $Models) == 1 && ( $oModelSettings->leave_as_array && ! in_array($sReferrer,@$oModelSettings->leave_as_array)))

                // Attach it directly
                $aExistingMembers[ $sReferrer ] = $Models[$keys[0]];

            else {


                $aExistingMembers[$label] = $Models;

            }

            $aExistingMembers["has_$label"] = count( $Models)>0;

            $aExistingMembers["num_$label"] = count( $Models);

        }

        $this->array_values = $aExistingMembers;

    }

    /**
     * @param $model_name
     * @param $referrer
     * @param $method_name
     * @param $query
     * @param Model_Factory $factory
     * @return array of Model
     */
    private function models( $model_name, $referrer, $method_name, $query, Model_Factory $factory ){

        if ( $this->debug ) \FB::info(get_called_class().": Calling factory method $method_name for $model_name referrer $referrer with query $query");


        // Get cache key
        $cache_key = new Cache_Key( $model_name. $referrer. $method_name . $query );

        return self::$cache->exists((string)$cache_key) ?

            self::$cache->get((string)$cache_key):

            $factory->$method_name($query);

    }
        
 }