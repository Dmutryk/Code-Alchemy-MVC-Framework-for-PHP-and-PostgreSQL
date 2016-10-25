<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/3/16
 * Time: 6:13 PM
 */

namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class InsFetch_Models
 * @package Code_Alchemy\Models\Factories
 *
 * InsFetches a bunch of Models
 */
class InsFetch_Models extends Array_Representable_Object{

    /**
     * InsFetch_Models constructor.
     * @param $model_name
     * @param array $reference_values
     * @param array $insert_values
     * @param bool $debug
     */
    public function __construct( $model_name, array $reference_values, array $insert_values = array(), $debug = false) {

        $models = array();
        $validate = array();
        $errors = array();

        $counter = 1;
        foreach ( $reference_values as $reference_value ){

            if ( $debug ) \FB::info(get_called_class().": Insfetch Model of type $model_name with reference value $reference_value");

            if( in_array($reference_value, $validate) ){
                $errors[] = "Value #".$counter;
            }
            else
            {
                $validate[] = $reference_value;
                $models[] = (new InsFetch_Model($model_name,$reference_value,$insert_values,$debug))->model();
            }
            $counter++;

        }

        $this->array_values = $models;
        $this->fails = $errors;

    }

}