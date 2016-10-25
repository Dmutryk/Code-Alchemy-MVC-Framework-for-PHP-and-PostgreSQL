<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/17/15
 * Time: 1:29 PM
 */

namespace Code_Alchemy\Core;


/**
 * Class Webroot
 * @package Code_Alchemy\Core
 *
 * Gets the webroot
 */
class Webroot extends Stringable_Object{

    public function __construct(){

        $this->string_representation = (string) Code_Alchemy_Framework::instance()->webroot();

    }
}