<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/18/15
 * Time: 9:24 AM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Referencee_Key_Column
 * @package Code_Alchemy\Models\Helpers
 *
 * Specifies the Key Column for a referencee, a foreign key
 */
class Referencee_Key_Column extends Stringable_Object {

    /**
     * @param string $canonical_name for referencee
     */
    public function __construct( $canonical_name ){

        $this->string_representation = in_array($canonical_name,array(
            'created_by','last_modified_by','deleted_by'
        ))? $canonical_name : $canonical_name."_id";

    }

}