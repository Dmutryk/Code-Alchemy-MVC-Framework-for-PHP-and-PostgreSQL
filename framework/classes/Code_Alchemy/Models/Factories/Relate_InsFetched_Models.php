<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/21/16
 * Time: 2:47 PM
 */

namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;

/**
 * Class Relate_InsFetched_Models
 * @package Code_Alchemy\Models\Factories
 *
 * Relates InsFetched Models to a Primary Model
 */
class Relate_InsFetched_Models {

    /**
     * Relate_InsFetched_Models constructor.
     * @param $insfetched_model_name
     * @param array $insfetch_search_values
     * @param $relationship_model_name
     * @param array $primary_model_members
     * @param $primary_model_name
     */
    public function __construct(
        $insfetched_model_name,
        array $insfetch_search_values,
        $relationship_model_name,
        array $primary_model_members,
        $primary_model_name
    ) {

        $primary_model_key = (string) new Key_Column($primary_model_name);


        foreach ( $insfetch_search_values as $insfetch_lookup_value ){

            $insfetched = (new InsFetch_Model($insfetched_model_name,$insfetch_lookup_value,array(

                'created_by' => @$primary_model_members['created_by']

            )))

                ->model();

            if ( $insfetched->exists ){

                $insfetch_key_column = (string) new Key_Column($insfetched_model_name);

                $relationship_model = (new Model($relationship_model_name))

                    ->create_from(array(

                        $insfetch_key_column => $insfetched->id(),

                        $primary_model_key => $primary_model_members[$primary_model_key],

                        'created_by' => @$primary_model_members['created_by']

                    ));
            }

        }


    }

}