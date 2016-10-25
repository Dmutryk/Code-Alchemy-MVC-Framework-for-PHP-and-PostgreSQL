<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/3/16
 * Time: 5:53 PM
 */

namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Models\Helpers\Reference_Column_For;
use Code_Alchemy\Models\Model;

/**
 * Class InsFetch_Model
 * @package Code_Alchemy\Models\Factories
 *
 * "InsFetch" means that we will either insert a new Model, or just fetch an existing one,
 *  based on a given key.
 *
 * It is somewhat similar to the idea of Upsert, except that there is no update, only insert or no
 * modifications.  In both cases, the Model is returned.
 */
class InsFetch_Model extends Alchemist {



    /**
     * @var Model
     */
    private $model;

    /**
     * InsFetch_Model constructor.
     * @param $model_name
     * @param $reference_value
     * @param array $insert_values
     * @param bool $debug
     */
    public function __construct( $model_name, $reference_value, array $insert_values = array(), $debug = false ) {

        $this->_debug = $debug;
        //$this->_debug = true;

        $reference_Column_For = (string) new Reference_Column_For($model_name);

        if ( ! $reference_Column_For ){

            \FB::warn(get_called_class().": Model $model_name doesn't have a Reference Column defined in models.json but one is required for InsFetch to work properly");

            return;
        }


        $reference_value = preg_replace('/,/','.',$reference_value);


        $reference_value2 = str_replace("'", "''", $reference_value);

        $find = $reference_Column_For . "='$reference_value2'";

        \FB::info("{ $reference_value }");
        \FB::info("{ $reference_value2 }");

        //\FB::info($find);

        if ( $this->_debug ) \FB::info(get_called_class().": Find is $find");


        $this->model = (new Model($model_name))

            ->find($find,'',$this->_debug);


        $insertable_Values = array_merge(array(

            $reference_Column_For => $reference_value

        ), $insert_values);


        if ( $this->_debug ) {

            \FB::info(get_called_class().": Insfetch $model_name $reference_Column_For is $reference_value, insert values next");

            \FB::info($insertable_Values);
        }


        if ( ! $this->model->exists ){

            if ( $this->_debug ) {

                \FB::info(get_called_class().": Inserting new Model $model_name with abovementioned insertable values");

            }

            $this->model = (new Model($model_name))

                ->create_from($insertable_Values);

            if ( ! $this->model->exists ){

                \FB::warn(get_called_class().": Insert failed ". $this->model->error());

            }

        } else {

            if ( $this->_debug ) \FB::info(get_called_class().": This Infetched model already exists");
        }


    }

    /**
     * @return Model
     */
    public function model(){ return $this->model; }

}