<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/20/15
 * Time: 10:23 AM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Models\Factories\Model_Factory;

/**
 * Class Dynamic_Data_Fetcher
 * @package Code_Alchemy\Models\Helpers
 *
 * Represent Dynamic Data for an object as an Array
 */
class Dynamic_Data_Fetcher extends Array_Representable_Object{

    /**
     * @param array $dynamic_data_specifications to fetch
     */
    public function __construct( array $dynamic_data_specifications ){

        $data = array();

        foreach ( $dynamic_data_specifications as $specification ){

            $method = $specification['query'];

            $data[ strtolower( (string) new CamelCase_Name( $$specification['seo_name'],'-','_' ) ) ]

                = (new Model_Factory($specification['model']))

                    ->$method( $specification['search'] );

        }

        $this->array_values = $data;

    }

}