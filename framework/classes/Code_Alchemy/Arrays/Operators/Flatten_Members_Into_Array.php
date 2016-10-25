<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/21/16
 * Time: 8:23 PM
 */

namespace Code_Alchemy\Arrays\Operators;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Flatten_Members_Into_Array
 * @package Code_Alchemy\Arrays\Operators
 *
 * Flatten one or more members from each item into a new flat array
 */
class Flatten_Members_Into_Array extends Array_Representable_Object{

    public  function __construct( array $subject, array $members_to_extract ) {

        $result = [];

        foreach( $subject as $item )

            foreach ( $members_to_extract as $member){

                if ( is_array($item[$member]))

                    $result = array_merge($result,$item[$member]);

                else $result[] = $item[$member];

            }

        $this->array_values = $result;

    }
}