<?php


namespace Code_Alchemy\Filesystem\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class One_Level_Up extends Stringable_Object {

    /**
     * @param string $full_path
     */
    public function __construct( $full_path ){

        $path_pieces = explode('/', $full_path);

        $this->string_representation = implode('/',array_splice($path_pieces,0,(count($path_pieces)-1)));

    }

}