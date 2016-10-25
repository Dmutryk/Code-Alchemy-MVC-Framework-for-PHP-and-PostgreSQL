<?php


namespace Code_Alchemy\Vendors\Facebook;


use Code_Alchemy\Core\Array_Representable_Object;

class Open_Graph_Tags extends Array_Representable_Object {

    /**
     * @param array $tags to set
     */
    public function __construct( array $tags ){

        foreach ( $tags as $name => $value )

            $this->$name = $value;

    }

}