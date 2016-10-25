<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Custom_Pre_Filter_Classname_For extends Stringable_Object {

    public function __construct( $model_name ){

        $this->string_representation = "\\".new Namespace_Guess(). "\\Models\\As_Array_Pre_Filters\\".new CamelCase_Name($model_name,'_','_');

    }

}