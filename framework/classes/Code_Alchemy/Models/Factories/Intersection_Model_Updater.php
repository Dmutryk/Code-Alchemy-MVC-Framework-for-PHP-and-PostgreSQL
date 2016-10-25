<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/19/15
 * Time: 7:21 PM
 */

namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Models\Factories\Helpers\Extract_One_Field_from_Models;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;

/**
 * Class Intersection_Model_Updater
 * @package Code_Alchemy\Models\Factories
 *
 * Allows you to reconcile an update to those pesky intersections between models
 */
class Intersection_Model_Updater extends Alchemist{

    /**
     * @param string $model_name for the intersection table
     * @param string $changed_field_name that will be the key to determine changes
     * @param array $current_intersections Models only please
     * @param array $changes list of values for changed_field_name
     * @param array $constant_values
     */
    public function __construct(
        $model_name,
        $changed_field_name,
        array $current_intersections,
        array $changes,
        array $constant_values

    ){

        // Step one, extract the changed field values from the current intersections
        $current_values = (new Extract_One_Field_from_Models($changed_field_name,$current_intersections))

            ->as_array();

        // For each of the current values
        foreach ( $current_values as $model_id => $value )

            // Not in change list?
            if ( ! in_array($value,$changes) )

                // Remove that model
                (new Model($model_name))->find(new Key_Column($model_name)."='$model_id'")->delete();

        // For each of the changes
        foreach ( $changes as $new_field_value )

            // If not in current list
            if ( ! in_array($new_field_value,$current_values))

                // Add it
                (new Model($model_name))

                    ->create_from(array_merge($constant_values,array(

                        $changed_field_name => $new_field_value

                    )));

    }

}