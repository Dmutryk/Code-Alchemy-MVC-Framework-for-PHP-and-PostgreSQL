<?php


namespace Code_Alchemy\Controllers;


use Code_Alchemy\Core\Stringable_Object;

class Controller_File_Full_Path extends Stringable_Object {

    /**
     * @param $canonical_name
     */
    public function __construct( $canonical_name ){

        $this->string_representation = getcwd()."/app/controllers/$canonical_name.php";

    }


}