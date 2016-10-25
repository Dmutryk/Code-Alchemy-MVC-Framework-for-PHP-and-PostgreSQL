<?php


namespace __namespace__\Models\Error_Filters;

use Code_Alchemy\Core\Stringable_Object;

/**
 * Class __classname__
 * @package __namespace__\Models\Triggers\After_Delete
 *
 * Use this Error Filter component to automatically filter any error messages
 * emitted by Models of this type.
 *
 */
class __classname__ extends Stringable_Object {

    /**
     * @param array $values
     */
    public function __construct( $error_text ){

        // By default, preserve text as-is, but you can change this
        $this->string_representation = $error_text;

    }

}