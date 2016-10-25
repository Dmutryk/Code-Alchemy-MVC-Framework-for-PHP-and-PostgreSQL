<?php


namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Stringable_Object;

class Timestamp extends Stringable_Object {

    public function __construct( $datetime = null ){

        $time = is_numeric( $datetime )? $datetime:
            ($datetime? strtotime( $datetime) : time() );

        $this->string_representation = date('g:iA D M jS',$time);

    }

}