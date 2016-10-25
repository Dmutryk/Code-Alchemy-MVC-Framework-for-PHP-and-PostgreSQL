<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/20/15
 * Time: 2:05 PM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Boolean_Member
 * @package Code_Alchemy\Models\Helpers
 *
 * Determines if a Member is a Boolean value or not
 */
class Is_Boolean_Member extends Boolean_Value{

    /**
     * @param string $member_name
     * @param mixed $value
     */
    public function __construct( $member_name, $value ){

        $this->boolean_value =

            (
                preg_match('/has_(.+)/',$member_name,$hits)

                ||

                preg_match('/is_(.+)/',$member_name,$hits)

                ||

                preg_match('/(.+)_only$/',$member_name,$hits)

                ||

                preg_match('/^should_(.+)/',$member_name,$hits)

            )

            && is_numeric($value);

    }

}