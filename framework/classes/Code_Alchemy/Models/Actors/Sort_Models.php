<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/27/15
 * Time: 12:05 AM
 */

namespace Code_Alchemy\Models\Actors;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;

/**
 * Class Sort_Models
 * @package Code_Alchemy\Models\Actors
 *
 * Sort some models
 */
class Sort_Models extends Array_Representable_Object{

    /**
     * Sort_Models constructor.
     * @param $model_name
     * @param array $ids
     */
    public function __construct( $model_name, array $ids) {

        $result = true;

        $sortable_id = 1;

        foreach ( $ids as $id ){

            $model = (new Model($model_name))

                ->find(new Key_Column($model_name) . "='$id'");

            $result &= $model

                ->update(array(

                    'sortable_id' => $sortable_id++

                ))->put();

            if ( ! $result){

                $this->error = $model->error();

                break;
            }
        }

        $this->ids = $ids;

        $this->result = $result ? 'success': 'error';


    }
}