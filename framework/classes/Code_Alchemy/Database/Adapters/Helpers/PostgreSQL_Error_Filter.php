<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/26/15
 * Time: 10:15 PM
 */

namespace Code_Alchemy\Database\Adapters\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class PostgreSQL_Error_Filter
 * @package Code_Alchemy\Database\Adapters\Helpers
 *
 * Provides a mechanism to filter PostgreSQL errors to be more meaningful and easier
 * to comprehend
 */
class PostgreSQL_Error_Filter extends Stringable_Object{

    /**
     * PostgreSQL_Error_Filter constructor.
     * @param $original_error
     */
    public function __construct( $original_error ) {

        if ( preg_match("/null value in column \"([a-zA-Z_0-9]+)\" violates not-null constraint/",$original_error,$hits))

            $original_error = "Column ".$hits[1]. " cannot be NULL";

        $this->string_representation = (string) $original_error;

    }

}