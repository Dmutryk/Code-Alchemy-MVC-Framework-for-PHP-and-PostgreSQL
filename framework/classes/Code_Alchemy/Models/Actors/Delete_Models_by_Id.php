<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/15/15
 * Time: 2:31 PM
 */

namespace Code_Alchemy\Models\Actors;


use Code_Alchemy\Core\Integer_Value;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;

/**
 * Class Delete_Models_by_Id
 * @package Code_Alchemy\Models\Actors
 *
 * Deletes a bunch of models by Id
 */
class Delete_Models_by_Id extends Integer_Value {

    /**
     * @var array of Ids for removed Models
     */
    private $removed_ids = [];

    /**
     * Delete_Models_by_Id constructor.
     * @param $model_name
     * @param array $ids
     */
    public function __construct( $model_name, array $ids, array $cascade_delete = array() ) {

        $num_deleted = 0;

        foreach ( $ids as $id ) {

            // Cascade delete
            if ( count( $cascade_delete) > 0 )

                foreach ( $cascade_delete as $cacade_model ){

                    $squery = $model_name . "_id='$id'";


                    $cascade_models = (new Model_Factory($cacade_model))

                        ->find_all($squery);

                    foreach ( $cascade_models as $cascadeable)

                        $cascadeable->delete();

                }


            $model = (new Model($model_name));

            if ( $model->find(new Key_Column($model_name)."='$id'")->delete()){

                $num_deleted++;

                $this->removed_ids[] = $model->id();

            }

            else {

                $error = " Error deleting $model_name $id: " . $model->error();

                $this->error .= $error;
            }
        }

        $this->integer_value = $num_deleted;

    }

    /**
     * @return array of removed Models' Ids
     */
    public function ids(){ return $this->removed_ids; }

}