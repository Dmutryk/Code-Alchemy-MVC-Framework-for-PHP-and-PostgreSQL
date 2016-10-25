<?php


namespace Code_Alchemy\helpers;


use Code_Alchemy\apis\directory_api;

class sql_types {

    /**
     * @var array of types
     */
    private $types = array();

    public function __construct(){

        global $codealchemy_location;

        $sql_types = new directory_api($codealchemy_location."/sql");

        foreach ( $sql_types->directory_listing() as $type ){

            if ( preg_match('/([a-z_]+)_table.sql/',$type,$hits)){

                $base = $hits[1];

                $name = ucfirst( implode(' ',explode('_',$base)));

                $this->types[ $name ] = $base;

            }


        }


    }

    /**
     * @return array of types
     */
    public function as_array(){

        return $this->types;
    }

}