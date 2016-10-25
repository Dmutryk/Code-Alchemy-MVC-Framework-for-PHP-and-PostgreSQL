<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/3/15
 * Time: 9:43 PM
 */

namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Last_Time_of_Month
 * @package Code_Alchemy\DateTime
 *
 * Gets the last time of the month in datetime format
 */
class Last_Time_of_Month extends Stringable_Object{

    /**
     * @param int $timestamp
     */
    public function __construct( $timestamp ){

        $this->string_representation = date('Y-m-t 23:59:59',$timestamp);

    }

}