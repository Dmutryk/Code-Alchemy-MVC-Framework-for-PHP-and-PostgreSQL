<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 22/04/14
 * Time: 05:17 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\components;


class namespace_parser {

    private $string_representation = '';

    private $final_part = '';

    public function __construct( $called_class ){

        $parts = explode('\\',$called_class);

        if ( count($parts)>1){

            $length = count($parts);

            for ( $index = 0;$index<($length-1);$index++)
                $this->string_representation .= ($this->string_representation)?'\\'.$parts[$index]:$parts[$index];

            $this->final_part = $parts[$length-1];

        } else $this->string_representation = $called_class;

    }

    public function __toString(){

        return $this->string_representation;

    }

    public static function create( $called_class ){

        return new self($called_class);

    }

    public function final_part(){

        return $this->final_part;

    }

}