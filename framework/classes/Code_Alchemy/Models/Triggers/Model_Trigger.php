<?php


namespace Code_Alchemy\Models\Triggers;


use Code_Alchemy\Core\Array_Representable_Object;

class Model_Trigger extends Array_Representable_Object {

    /**
     * @var array of additional parameters for Custom Trigger
     */
    protected $parameters = array();

    /**
     * @param $trigger_type
     * @param $model_name
     * @param bool|false $is_changed
     * @param array $current_model_members
     * @param array $previous_values
     * @param string $insert_error
     */
    protected function fire_trigger(
        $trigger_type,
        $model_name,
        &$is_changed = false,
        array $current_model_members = array(),
        array $previous_values = array(),
        &$insert_error = ''

    ){

        // Get custom trigger class
        $class = (string) new Custom_Trigger_Class($model_name,$trigger_type);

        // Class doesn't exist?  In dev mode?
        if ( ! class_exists($class) && $this->is_development() )

            // Create the custom trigger on behalf of the user
            new Create_Custom_Trigger($class,$trigger_type,$model_name);

        if ( class_exists($class) ){

            // If is subclass
            if ( is_subclass_of($class,"\\Code_Alchemy\\Models\\Triggers\\Custom_Trigger")) {

                // Get the custom trigger
                $custom_trigger = (new $class(

                    // For after update pass previous values
                    $trigger_type == 'after_update' ? $previous_values : $this->array_values,

                    // For Before /After Update share current members with Custom Trigger
                    in_array( $trigger_type, array('before_update','after_update')) ?

                        $current_model_members :

                            $this->parameters

                    ,

                    // for After update share if changed
                    $is_changed

                ));

                // Set values
                $this->array_values = $custom_trigger->as_array();

                // Bubble changes back up the chain of command
                $is_changed = $custom_trigger->is_changed();

                // Bubble error back for insert
                if ( preg_match('/before_insert/i',$class) && method_exists($custom_trigger,'insert_error'))

                    $insert_error = $custom_trigger->insert_error();


            }

            else

                \FB::warn("$class: This is not a subclass of Custom_Trigger");
        }

    }

}