<?php


namespace Code_Alchemy\Cache;


use Code_Alchemy\Core\Stringable_Object;

class Cache_Key extends Stringable_Object {

    /**
     * @param string $string to encode as a Cache Key
     */
    public function __construct( $string ){

        $this->string_representation = md5( $string );
    }

}