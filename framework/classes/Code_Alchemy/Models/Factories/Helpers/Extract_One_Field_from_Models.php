<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/19/15
 * Time: 7:29 PM
 */

namespace Code_Alchemy\Models\Factories\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Extract_One_Field_from_Models
 * @package Code_Alchemy\Models\Factories\Helpers
 *
 * Extracts one field from each of a collection of Models, and puts it into
 * an associative array by Id of model
 */
class Extract_One_Field_from_Models extends Array_Representable_Object {

    /**
     * @param string $member_name to extract
     * @param array $models to traverse
     */
    public function __construct( $member_name, array $models ){

        foreach ( $models as $model )

            $this->array_values[ $model->id() ] = @$model->$member_name;


    }
}