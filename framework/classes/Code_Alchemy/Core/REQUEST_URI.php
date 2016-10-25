<?php

namespace Code_Alchemy\Core;

class REQUEST_URI {

	public function part( $num){

        $parsed_url = parse_url( "http://". @$_SERVER['HTTP_HOST']. @$_SERVER['REQUEST_URI']);

        $parts = explode('/',$parsed_url['path']);

        $part = isset( $parts[(int)$num])?preg_replace( '/%20/',' ', $parts[ (int)$num ]):null;

		return $part;
	}

    /**
     * @return REQUEST_URI
     */
    public static function create(){ $c = __CLASS__; return new $c(); }
    public function __toString(){
        $str = '';
        $parts = explode('/', @$_SERVER['REQUEST_URI']);
        foreach ( $parts as $part) $str .= $part. ",";
        return $str;
    }

    /**
     * @return array representation of URI
     */
    public function as_array(){

        return explode('/', @$_SERVER['REQUEST_URI']);

    }
}

?>