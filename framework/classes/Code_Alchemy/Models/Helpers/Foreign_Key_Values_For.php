<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 1/10/15
 * Time: 2:49 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Factories\Factory_Wrapper;
use Code_Alchemy\Models\Helpers\Model_Class_For;
use Code_Alchemy\Models\Model_Configuration;
use Code_Alchemy\models\model_wrapper;
use Code_Alchemy\tools\code_tag;

class Foreign_Key_Values_For extends  Alchemist{

    /**
     * @var array of values
     */
    private $values = array();


    /**
     * @param string $model_name to fetch
     * @param string $member to fetch with id
     */
    public function __construct( $model_name, $member = 'name' ){

        $this->_firebug = true;

        $model_class = (string) new Model_Class_For( $model_name );

        if ( $this->_firebug) \FB::info(get_called_class().": Model is $model_name Model Class is $model_class and member is $member");

        if ( $model_class ){

            $model = new Factory_Wrapper(

                (new Model_Class_Verifier($model_class))->is_dynamic_model()?

                    (new Dynamic_Model($model_name))->get_factory():

                        $model_class::factory()

            );

            $is_safe_delete = !! (new Model_Configuration())->model_for($model_name)['safe_delete'];

            $raw  = $is_safe_delete ? $model->model()->find_all_undeleted('',true,$this->firebug):

            $model->model()->find_all('',true,$this->firebug);


            if ( $this->firebug ) \FB::log($raw);

            foreach ( $raw as $item ) {


                $value =  $item->$member() ? $item->$member(): $item->$member;

                if ( $this->firebug ) \FB::log("$tag->firebug_format: Found Item $item->id value is $value");

                $this->values[ $item->id() ] = $value;
            }

        }


    }

    /**
     * @return array of values
     */
    public function values( ){

        return $this->values;

    }

}