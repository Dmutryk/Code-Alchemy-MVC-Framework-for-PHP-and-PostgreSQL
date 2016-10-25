<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/3/15
 * Time: 9:39 PM
 */

namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class First_Time_of_Month
 * @package Code_Alchemy\DateTime
 *
 * Gets the first time of month in format Y-m-d H:i:s
 */
class First_Time_of_Month extends Stringable_Object {

    /**
     * @param int $timestamp
     */
    public function __construct( $timestamp ){

        $this->string_representation =

            date('Y-m-01 00:00:00',$timestamp);

    }

}