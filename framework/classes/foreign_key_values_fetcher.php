<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 1/10/15
 * Time: 2:49 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\helpers;


use Code_Alchemy\Models\Helpers\Model_Class_For;
use Code_Alchemy\models\model_wrapper;
use Code_Alchemy\tools\code_tag;

class foreign_key_values_fetcher {

    /**
     * @var array of values
     */
    private $values = array();

    /**
     * @var bool true to enable logging to Firebug
     */
    private $firebug = true;

    /**
     * @param string $model_name to fetch
     * @param string $member to fetch with id
     */
    public function __construct( $model_name, $member = 'name' ){

        $tag = new code_tag(__FILE__,__LINE__,get_class(),__FUNCTION__);

        if ( $this->firebug ) \FB::log("$tag->firebug_format: Model name is $model_name and member is $member");

        $model_class = (string) new Model_Class_For( $model_name );

        if ( $model_class ){

            $model = new model_wrapper( $model_class::model() );

            $raw  = $model->model()->find_all_undeleted('',true,$this->firebug);

            if ( $this->firebug ) \FB::log($raw);

            foreach ( $raw as $item ) {


                $value = $item->$member()?$item->$member():$item->$member;

                if ( $this->firebug ) \FB::log("$tag->firebug_format: Found Item $item->id value is $value");

                $this->values[ $item->id ] = $value;
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