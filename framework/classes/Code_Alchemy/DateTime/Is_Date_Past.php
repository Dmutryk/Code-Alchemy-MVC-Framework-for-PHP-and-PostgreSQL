<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/6/16
 * Time: 9:37 AM
 */

namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Date_Past
 * @package Code_Alchemy\DateTime
 *
 * Is this date in the past?
 */
class Is_Date_Past extends Boolean_Value{

    /**
     * Is_Date_Past constructor.
     * @param $datestring
     */
    public function __construct( $datestring ) {

        $this->boolean_value = $datestring < date('Y-m-d H:i:s');

    }

}