<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/3/16
 * Time: 4:58 PM
 */

namespace Code_Alchemy\Models\Factories;

use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Similar_Models
 * @package Code_Alchemy\Models\Factories
 *
 * Helps to find Models similar to a given text
 */
class Similar_Models extends Array_Representable_Object{

    /**
     * @var array of similar values
     */
    private $similar_values = array();

    /**
     * Similar_Models constructor.
     * @param $search_model_name
     * @param $search_column
     * @param $search_text
     * @param float $threshold
     * @param bool $exclude_identical
     */
    public function __construct( $search_model_name, $search_column,$search_text, $threshold = 80.0, $exclude_identical = false, $debug = false ) {

        $this->_debug = $debug;     // Passo thrrough user debug choice

        $results = array();

        $models = (new Model_Factory($search_model_name))->find_all();

        if ( $this->_debug ) \FB::info(get_called_class().": Finding similar for $search_model_name $search_column $search_text $threshold checking ". count($models). " models");

        foreach ($models as $model ){

            $percent = 0.0;

            similar_text($model->$search_column,$search_text,$percent);

            if ( $this->_debug) \FB::info(get_called_class().": percent similar is $percent");

            if ( (float)$percent >= (float)$threshold ){

                if ( ! $exclude_identical || (float)$percent < 100.00 ){

                    $this->similar_values[] = $model->$search_column;

                    $results[] = [

                        'model' => $model->as_array(),

                        'percent' => $percent

                    ];

                }

            }

        }

        $this->array_values = $results;

    }

    /**
     * @return array of similar values
     */
    public function similar_values(){ return $this->similar_values; }
}