<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 24/09/13
 * Time: 09:54 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class RequestToken {

    /**
     * @var string declaration of requestor
     */
    private $declaration = '';

    public function __construct( $declaration ){

        $this->declaration = $declaration;

    }

    public function declaration(){ return (string) $this->declaration; }

}