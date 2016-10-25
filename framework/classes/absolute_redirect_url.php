<?php


namespace Code_Alchemy\helpers;


class absolute_redirect_url {

    /**
     * @var string Absolute redirect, as calculated from local redirect
     */
    private $absolute_redirect = '';

    /**
     * @param string $local_redirect to calculate
     */
    public function __construct( $local_redirect = '/' ){

        $this->absolute_redirect = "http://".$_SERVER['HTTP_HOST'].$local_redirect;

    }

    /**
     * @return string Absolute redirect
     */
    public function __toString(){

        return $this->absolute_redirect;
    }

}