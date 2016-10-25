<?php

namespace Code_Alchemy\Users\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Unique_User_Id
 * @package Code_Alchemy\Users\Helpers
 *
 * Generate a Unique user Id
 */
class Unique_User_Id extends Stringable_Object {

    public function __construct() {

        $this->string_representation =
            sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff));
    }


}