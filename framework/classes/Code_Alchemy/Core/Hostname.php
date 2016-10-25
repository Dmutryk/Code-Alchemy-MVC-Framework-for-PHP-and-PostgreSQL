<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/12/15
 * Time: 10:27 PM
 */

namespace Code_Alchemy\Core;

/**
 * Class Hostname
 * @package Code_Alchemy\Core
 *
 * Gets the hostname, regardless of execution as webscript or command line
 */
class Hostname extends Stringable_Object {

    public function __construct() {

        $this->string_representation = isset( $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']

            ? $_SERVER['HTTP_HOST']: (string) (new Configuration_File())->find('hostname');

    }

}