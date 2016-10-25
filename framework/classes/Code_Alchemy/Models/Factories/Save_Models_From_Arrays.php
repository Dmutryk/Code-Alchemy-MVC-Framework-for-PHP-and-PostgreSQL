<?php namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Model;

/**
 * Class Save_Models_From_Arrays
 * @package Code_Alchemy\Models\Factories
 *
 * Saves a bunch of Models, of the same type, from an array of arrays
 */
class Save_Models_From_Arrays extends Array_Representable_Object{

    /**
     * Save_Models_From_Arrays constructor.
     * @param $modelName
     * @param array $seeds
     */
    public function __construct( $modelName, array $seeds ) {

        $result = ['numSaved' => 0,'newIds' => [],'errors' => [] ];

        foreach ( $seeds as $seedValues ) {

            $model = (new Model($modelName));

            if ( $model->create_from( $seedValues )->exists ){

                $result['numSaved']++;

                $result['newIds'][] = $model->id();

            } else $result['errors'][] = $model->error();

        }

        $this->array_values = $result;
    }
}