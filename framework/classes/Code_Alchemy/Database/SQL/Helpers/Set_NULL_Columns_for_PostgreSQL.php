<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/2/15
 * Time: 12:17 PM
 */

namespace Code_Alchemy\Database\SQL\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Set_NULL_Columns_for_PostgreSQL
 * @package Code_Alchemy\Database\SQL\Helpers
 *
 * When one of these columns is empty, we set to NULL for PostgreSQL
 */
class Set_NULL_Columns_for_PostgreSQL extends Array_Representable_Object{

    public function __construct(){

        $this->array_values = array(

            'created_by',

            'last_modified_date',

            'last_modified_by',

            'deleted_date',

            'deleted_by'

        );
    }
}
