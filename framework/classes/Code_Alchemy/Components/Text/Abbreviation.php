<?php


namespace Code_Alchemy\Components\Text;


use Code_Alchemy\Core\Stringable_Object;

class Abbreviation extends Stringable_Object{

    public function __construct( $word, $length, $overflow_indicator = '...'){

        $add = strlen($word)>$length?$overflow_indicator:'';

        $this->string_representation = substr($word,0,$length).$add;


    }

}