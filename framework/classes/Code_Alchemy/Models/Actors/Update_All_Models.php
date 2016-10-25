<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/17/16
 * Time: 11:37 AM
 */

namespace Code_Alchemy\Models\Actors;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Factories\Model_Factory;

/**
 * Class Update_All_Models
 * @package Code_Alchemy\Models\Actors
 *
 * Update all Models, referenced by Ids, with a single set of changes
 */
class Update_All_Models extends Array_Representable_Object{

    /**
     * Update_All_Models constructor.
     * @param string $model_name
     * @param array $model_ids
     * @param array $changes
     */
    public function __construct( $model_name, array $model_ids, array $changes ) {

        $result = true;

        $errors = [];

        // For each model
        foreach( (new Model_Factory($model_name))->fetch_all_from_ids($model_ids) as $model ){

            $result &= $model->update($changes)->put();

            if ( ! $result)  $errors[] =  $model->id(). ": ". $model->error();

        }

        $this->array_values = [

            'result' => $result? 'success': 'error',

            'errors' => $errors
        ];

    }

}