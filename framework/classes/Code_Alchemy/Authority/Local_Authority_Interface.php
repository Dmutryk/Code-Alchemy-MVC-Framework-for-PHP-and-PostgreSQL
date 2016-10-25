<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/27/16
 * Time: 8:00 PM
 */

namespace Code_Alchemy\Authority;


/**
 * Interface Local_Authority_Interface
 * @package Code_Alchemy\Authority
 *
 * provides an interface for apps to define their local authority
 */
interface Local_Authority_Interface {

    /**
     * @return bool true if current user is administrative
     */
    public function is_administrative_user();

}