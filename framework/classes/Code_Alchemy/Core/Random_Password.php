<?php

namespace Code_Alchemy\Core;

/**
 * Class Random_Password
 * @package Code_Alchemy\Core
 *
 * Generate a random password, or token
 */
class Random_Password {

    /**
     * Alphanumeric password/token type
     */
    const alphanumeric = 1;

    const unique_object_id = 2;

    /**
     * @var int length of password
     */
    private $length = 1;

    /**
     * @var int Type to generate
     */
    private $type = self::alphanumeric;



    /**
     * @var array of charsets
     */
    private $charsets = array(

        self::alphanumeric => "abcdefghijkmnopqrstuvwxyz023456789",

        self::unique_object_id => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"

    );

    /**
     * @param int $length of password
     * @param int $type of password to gen
     */
    public function __construct($length, $type = self::alphanumeric ){

        $this->length = $length;

        $this->type = $type;

    }


    public function __toString() {
        // the character set to create the password
        $chars = $this->charsets[ $this->type ];

        // initialize a randomized number
        srand((double)microtime()*1000000);

        $i = 0;

        $pass = '' ;

        // ( you can tell a C programmer wrote this code :-)
        while ($i < $this->length) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }
}
