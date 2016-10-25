<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 3/10/13
 * Time: 11:42 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\helpers;


class FriendlyErrorMessage {

    private $message = '';

    public function __construct( $message ){

        $this->message = $this->process( (string)$message);

    }

    private function process( $message ){

        if ( preg_match('/A record already exists with ([a-z|A-Z|0-9|_]+) = \'(.+)\'(\(INSERT)/',$message,$hits))
            $message = $hits[2] . ": This record already exists";

        return $message;

    }

    public function __toString(){

        return $this->message;

    }

}