<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/24/16
 * Time: 3:41 PM
 */

namespace Code_Alchemy\Models\Factories;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Model_Collection
 * @package Code_Alchemy\Models\Factories
 *
 * A Collection of Models, of a particular type, using a specific query
 */
class Model_Collection extends Array_Representable_Object{

    /**
     * Model_Collection constructor.
     * @param string $modelName
     * @param string $searchQuery
     */
    public function __construct( $modelName, $searchQuery ) {

        $this->array_values = (new Model_Factory($modelName))

            ->all_undeleted_as_array($searchQuery);

    }

}