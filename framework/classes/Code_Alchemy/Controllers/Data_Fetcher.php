<?php


namespace Code_Alchemy\Controllers;


use Code_Alchemy\Controllers\Helpers\Controller_Key;
use Code_Alchemy\Controllers\Helpers\Data_Specification;
use Code_Alchemy\Controllers\Helpers\Scope_Adder;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Creators\Database_Table_Creator;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Text_Operators\Text_Template;

/**
 * Class Data_Fetcher
 * @package Code_Alchemy\Controllers
 *
 * The Data Fetcher is responsable for fetching packets of data stipulated
 * by the user in controllers.json
 */
class Data_Fetcher extends Array_Representable_Object {

    /**
     * @param array $specification that comes from controllers.json, and indicates
     * @param array $substitutions to perform in search strings
     * @param array $scope of current values from Controller
     * what data to fetch, including model, template, and query
     */
    public function __construct(

        array $specification,

        array $subtitutions = array(),

        array $scope = array()

    ){

        // For each component
        foreach ( $specification as $name =>$spec ){

            // Skip when excluded
            if ( $this->_is_excluded( $spec )){

                continue;
            }



            $spec = new Data_Specification($spec);

            // Get database
            $database = new Database();

            // Does table exist?
            if ( ! $database->has_table($spec->table_name())){

                // If not, create it
                $creator = new Database_Table_Creator($spec->table_name(),$spec->type);

                $creator->set_options(array(
                    'references'=>$spec->references
                ));

                $creator->create(false);
            }

            // Create directory, if needed
            $spec->create_directory();


            // Does model exist?
            if ( ! $spec->model_exists() )

                // If not, create it
                $spec->create_model();

            // Get the factory
            $factory = new Model_Factory($spec->model);

            // What was the query?
            $query = $spec->query;

            // What was the search?
            $search = (string) new Text_Template((string) new Scope_Adder($spec->search,$scope),$subtitutions);

            if ( method_exists($factory,$query)){


                // Set final data
                $factory_result = $factory->$query($search ? $search : null);

                if ( ! count( $factory_result) ){

                    //if ( $this->is_development() ) \FB::warn(get_called_class().": $query( $search ): It's possible this search returned an empty result.  Check the query for validity");

                }


                $this->array_values[ $name ] = $factory_result;



                // Add to scope
                $scope = array_merge( $scope, $this->array_values);

            }


            else

                \FB::warn("$query: Code_Alchemy doesn't support this factory method for models");


        }
    }

    /**
     * @param array $specification
     * @return bool true if excluded
     */
    private function _is_excluded( array $specification ){

        $exclude = @$specification['exclude'];

        return is_array($exclude) && in_array((string) new Controller_Key(),$exclude);

    }

}