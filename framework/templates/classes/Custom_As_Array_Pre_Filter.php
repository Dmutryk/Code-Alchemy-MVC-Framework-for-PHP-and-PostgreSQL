<?php namespace __app_namespace__\Models\As_Array_Pre_Filters; use Code_Alchemy\Core\Array_Object; use Code_Alchemy\Models\Dynamic_Model; use Code_Alchemy\Models\Helpers\Custom_As_Array_Pre_Filter;
/**
 * Class __custom_prefilter_name__
 * @package __app_namespace__\Controllers
 *
 * Use this Custom As Array Pre filter to setup as array members for a Model,
 * before being sent to the client.  This code executes after the inherent pre-filter
 *
 * (c) 2015 Alquemedia SAS <info@alquemedia.com>*
 */
class __custom_prefilter_name__ extends Custom_As_Array_Pre_Filter {

    /**
     * __custom_prefilter_name__ constructor.
     * @param array $scope where you may inject new members to be accessible via the client
     * @param Dynamic_Model $model where you may access a copy of the Model
     * @param Array_Object $inheritedScope which gives you read-only access to all inherited
     * scope members.
     */
    public function __construct( array &$scope, Dynamic_Model $model, Array_Object $inheritedScope ){

        // TODO Add your custom filter actions here

    }

}