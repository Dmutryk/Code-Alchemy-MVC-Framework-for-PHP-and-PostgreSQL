<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/7/15
 * Time: 8:25 AM
 */

namespace Code_Alchemy\Arrays;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Trimmed_Array
 * @package Code_Alchemy\Arrays
 *
 * Takes an array and trims all members
 */
class Trimmed_Array extends Array_Representable_Object {

    /**
     * @param array $original array to trim
     */
    public function __construct( array $original ){

        array_walk($original,function(&$member,$index){

            if ( ! $member)

                unset( $member);

            else

                $member = trim($member);

        });

        $this->array_values = $original;

    }

}