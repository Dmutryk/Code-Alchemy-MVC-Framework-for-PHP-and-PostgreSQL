<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/22/16
 * Time: 4:58 PM
 */

namespace Code_Alchemy\Arrays\Transformers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\CamelCase_Name;

/**
 * Class Members_to_Mixed_Case_with_Spaces
 * @package Code_Alchemy\Arrays\Transformers
 *
 * Switches all members to Mixed Case with Spaces
 */
class Members_to_Mixed_Case_with_Spaces extends Array_Representable_Object{

    /**
     * Members_to_Mixed_Case_with_Spaces constructor.
     * @param array $original
     */
    public function __construct( array $original ) {

        $new = [];

        foreach( $original as $name => $value )

            $new[$name] = (string) new CamelCase_Name($value,'_',' ');

        $this->array_values = $new;

    }
}