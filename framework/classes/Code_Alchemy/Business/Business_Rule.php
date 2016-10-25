<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/23/15
 * Time: 10:00 AM
 */

namespace Code_Alchemy\Business;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Business_Rule
 * @package Code_Alchemy\Business
 *
 * Applies a single business rule, and sets a result
 */
class Business_Rule extends Boolean_Value{

    public function __construct( callable $rule, array &$result, &$is_valid ){



    }

}