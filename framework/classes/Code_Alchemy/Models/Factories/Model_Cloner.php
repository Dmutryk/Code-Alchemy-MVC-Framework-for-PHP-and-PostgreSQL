<?php


namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Model;

/**
 * Class Model_Cloner
 * @package Code_Alchemy\Models\Factories
 *
 * Clones a Model
 */
class Model_Cloner extends Alchemist{

    /**
     * @var \Code_Alchemy\Models\Model
     */
    private $clone;

    /**
     * Model_Cloner constructor.
     * @param Dynamic_Model $original
     * @param array $overrides
     */
    public function __construct( Dynamic_Model $original, array $overrides = array() ){

        $original_model_name1 = $original->model_name();

        \FB::info($original->id(),get_called_class());

        $model = new Model($original_model_name1);

        // Get cloning rules
        $cloning_rules = $original->cloning_rules();

        if ( $cloning_rules ){

            // Collate all values
            $values = $overrides;

            // If custom renamed fields
            if ( isset( $cloning_rules['custom_renamed_fields']))

                foreach( $cloning_rules['custom_renamed_fields'] as $field => $custom_method )

                    $values[$field] = $original->custom_method($custom_method,array(),false);

            // Copy over fields
            $seed_values = array_merge($original->members_as_array($cloning_rules['copy_fields']),$overrides);

            if ( ! $model->create_from($seed_values)->exists

            )
            {

                \FB::error("Unable to clone Model: ".$model->error());

                $this->error = $model->error();

            } else {

                $this->is_cloned = true;

                // Now check if we should clone referencing
                if ( isset( $cloning_rules['clone_referencing']))

                    // Foreach one
                    foreach ( $cloning_rules['clone_referencing'] as $model_name ){

                        $searchQuery = $original_model_name1 . "_id='" . $original->get($original->key_column()) . "'";

                        $referencing = (new Model_Factory($model_name))

                            ->find_all_undeleted($searchQuery);

                        // For each Model that references the Original...
                        foreach ( $referencing as $refmodel ){

                            // Clone it..
                            $member_name = $model->key_column();

                            $newref = $refmodel->_clone(array(

                                // Point it to the new model
                                $original_model_name1."_id" => $model->get($member_name)

                            ));

                        }


                    }

            }

        } else {

            \FB::warn(get_called_class().": No cloning rules for Model ". $original_model_name1);
        }
        $this->clone = $model;

    }

    /**
     * @return Model
     */
    public function get_clone(){ return $this->clone; }

}