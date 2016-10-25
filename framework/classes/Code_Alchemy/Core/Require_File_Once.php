<?php


namespace Code_Alchemy\Core;


class Require_File_Once {

    public function __construct( $file ){

        if ( $file ) require_once $file;

    }
}