<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Models\Dynamic_Model;

class Key_Column_For extends Stringable_Object{

    public function __construct( $table_name ){

        $this->string_representation =  (new Dynamic_Model($table_name))->key_column();

    }

}