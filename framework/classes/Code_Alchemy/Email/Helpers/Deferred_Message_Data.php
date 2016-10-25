<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/12/15
 * Time: 1:55 PM
 */

namespace Code_Alchemy\Email\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Interfaces\Model_Interface;
use Code_Alchemy\Models\Model;

/**
 * Class Deferred_Message_Data
 * @package Code_Alchemy\Email\Helpers
 *
 * Data for a deferred message
 */
class Deferred_Message_Data extends Array_Representable_Object{

    /**
     * Deferred_Message_Data constructor.
     * @param Model $deferred
     */
    public function __construct( Model_Interface $deferred ) {

        $this->array_values = (new Model($deferred->model_name ))

            ->find("id='$deferred->model_id'")->as_array();

    }
}