<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/21/16
 * Time: 12:55 PM
 */

namespace Code_Alchemy\Models\Actors;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Models\Model;

/**
 * Class Upsert_Model
 * @package Code_Alchemy\Models\Actors
 *
 * Upsert a Model
 */
class Upsert_Model extends Alchemist{

    /**
     * Upsert_Model constructor.
     * @param $model_name
     * @param $query
     * @param array $insert_values
     */
    public function __construct( $model_name, $query, array $insert_values ) {

        $model = (new Model($model_name))->find($query);

        if ( ! $model->exists )

            $model->create_from($insert_values);

        else

            $model->update($insert_values)->put();

    }
}