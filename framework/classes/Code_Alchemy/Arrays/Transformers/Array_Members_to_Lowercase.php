<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/19/16
 * Time: 10:18 AM
 */

namespace Code_Alchemy\Arrays\Transformers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Array_Members_to_Lowercase
 * @package Code_Alchemy\Arrays\Transformers
 *
 * Transforms all members to lowercase
 */
class Array_Members_to_Lowercase extends Array_Representable_Object{

    /**
     * Array_Members_to_Lowercase constructor.
     * @param array $original
     */
    public function __construct( array $original ) {

        $result = [];

        foreach ( $original as $name => $value )

            $result[$name] = strtolower($value);

        $this->array_values = $result;

    }
}