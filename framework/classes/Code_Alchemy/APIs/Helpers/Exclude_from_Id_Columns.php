<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/5/15
 * Time: 12:36 AM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Exclude_from_Id_Columns
 * @package Code_Alchemy\APIs\Helpers
 *
 * Don't use any of the following as Id columns
 */
class Exclude_from_Id_Columns extends Array_Representable_Object{

    public function __construct(){

        $this->array_values = array(

            'lowest_id'

        );

    }
}