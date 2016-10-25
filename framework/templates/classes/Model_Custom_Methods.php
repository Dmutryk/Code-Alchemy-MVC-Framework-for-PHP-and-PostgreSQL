<?php


namespace __app_namespace__\Models\Custom_Methods;
use Code_Alchemy\Models\Helpers\Model_Custom_Methods;


/**
 * Class __custom_methods_name__
 * @package __app_namespace__\Controllers
 *
 * Use this Component to define Custom Methods for your Model
 *
 * (c) 2015 Alquemedia SAS <info@alquemedia.com>
 *
 */
class __custom_methods_name__ extends Model_Custom_Methods {

    /**
     * @param array $model_members
     */
    public function __construct( array &$model_members ){

        // Add your custom methods actions here

    }

    /**
     * @param $method_name
     * @param $method_args
     * @param array $model_members
     * @param string $model_error to bubble up errors to Model
     * @return null
     */
    public function call_method( $method_name, $method_args, array &$model_members, &$model_error ){

        $result = null;

        if ( method_exists($this,$method_name))

            $result = $this->$method_name( $model_members, $method_args, $model_error );

        return $result;
    }

}