<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/8/15
 * Time: 4:44 PM
 */

namespace Code_Alchemy\Core;


/**
 * Class Canonical_Classname
 * @package Code_Alchemy\Core
 *
 * Gets the canonical classname from a full classname
 */
class Canonical_Classname extends Stringable_Object {

    /**
     * Canonical_Classname constructor.
     * @param $full_classname
     */
    public function __construct( $full_classname ){

        $this->string_representation = array_pop( explode('\\',$full_classname));

    }

}