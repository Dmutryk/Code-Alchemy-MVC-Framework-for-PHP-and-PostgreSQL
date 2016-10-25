<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/21/16
 * Time: 2:29 PM
 */

namespace Code_Alchemy\Models\Actors;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Helpers\Key_Column_For;
use Code_Alchemy\Models\Model;

/**
 * Class Impose_Single_Level_Hierarchy
 * @package Code_Alchemy\Models\Actors
 *
 * Takes a [] of Models as well as a column indicating relationship, and:
 *
 * 1) Moves related (child) models under parents
 * 2) Leaves parents alone
 * 3) Only for one level of hierarchy
 *
 * Resulting array is recomposition based on above actions
 */
class Impose_Single_Level_Hierarchy extends Array_Representable_Object{

    /**
     * Impose_Single_Level_Hierarchy constructor.
     * @param array $models
     * @param $reference_column_name
     * @param $model_name
     */
    public function __construct( array $models, $reference_column_name, $model_name ) {

        $imposees = [];

        $referents = [];

        // First add all who don't have referents
        foreach ( $models as $model) {

            $key = $model[(string)new Key_Column_For($model_name)];

            if (!@$model[$reference_column_name])

                $imposees[$key] = $model;

            else

                $referents[] = $model;

        }

        // Now place all referents
        foreach ( $referents as $model2 ){

            $key = $model2[$reference_column_name];

            if ( ! isset( $imposees[ $key ]['menu_items']))

                $imposees[ $key] ['menu_items'] = array();

            $imposees[ $key] ['menu_items'][] = $model2;

        }

        $this->array_values = $imposees;

    }

}