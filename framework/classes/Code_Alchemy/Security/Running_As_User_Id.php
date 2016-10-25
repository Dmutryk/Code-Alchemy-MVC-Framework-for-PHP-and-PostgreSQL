<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/25/16
 * Time: 10:42 AM
 */

namespace Code_Alchemy\Security;


use Code_Alchemy\Core\Integer_Value;

/**
 * Class Running_As_User_Id
 * @package Code_Alchemy\Security
 *
 * Id of Running User, which could be different from Logged In User
 */
class Running_As_User_Id extends Integer_Value{

    public function __construct() {

        $officer = (new Officer());

        $me = $officer->me();

        $this->integer_value = $officer->running_as() ? $officer->running_as():

            $me->id();

    }
}