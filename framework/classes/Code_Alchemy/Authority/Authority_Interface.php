<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/3/16
 * Time: 2:38 PM
 */

namespace Code_Alchemy\Authority;

/**
 * Interface Authority_Interface
 * @package Code_Alchemy\Authority
 *
 * The Authority Interface is created for implementing a Permssions Manager or other
 * Authority figure to determine if the current user is allowed to do something, as specified
 */
interface Authority_Interface {

    /**
     * Checks if I, the current user, may do something
     * @param string $user_right
     * @return bool true if I may do the specified action
     */
    public function may_i( $user_right );

}