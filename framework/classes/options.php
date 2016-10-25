<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 2/10/13
 * Time: 05:19 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\helpers;


class options {

    private $object = null;

    public function __construct( $object ){

        $this->object = $object;

    }

    public function using ( $array ){

        foreach ( $array as $name=>$value ){

            if ( property_exists(get_class($this->object),$name))
                $this->object->$name = $value;

        }

    }

    public static function set( $object ){

        return new self( $object );

    }

}