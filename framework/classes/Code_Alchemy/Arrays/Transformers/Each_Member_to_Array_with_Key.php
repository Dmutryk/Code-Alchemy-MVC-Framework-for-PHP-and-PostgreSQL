<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/22/16
 * Time: 5:08 PM
 */

namespace Code_Alchemy\Arrays\Transformers;



use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Each_Member_to_Array_with_Key
 * @package Code_Alchemy\Arrays\Transformers
 *
 * Take each member of a simple array and turn it into an embedded array with a given
 * set key
 *
 * For example:
 *
 * $start [ 'one' , 'two', 'three' ]
 *
 * $end [ [ 'key'=> 'one'] , ['key' => 'two'] , [ 'key'=>'three'] ];
 */
class Each_Member_to_Array_with_Key extends Array_Representable_Object{

    /**
     * Each_Member_to_Array_with_Key constructor.
     * @param array $original
     * @param string $key to add to each newly created subarray
     */
    public function __construct( array $original, $key ) {

        $new = [];

        foreach ( $original as $value )

            $new[] = [ $key => $value ];

        $this->array_values = $new;
    }
}