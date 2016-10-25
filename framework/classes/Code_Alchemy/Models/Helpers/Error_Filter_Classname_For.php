<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Error_Filter_Classname_For extends Stringable_Object {

    public function __construct( $model_name ){

        $this->string_representation = (preg_match('/^([a-zA-Z0-9_]+)$/',$model_name)) ?

            "\\".new Namespace_Guess(). "\\Models\\Error_Filters\\".new CamelCase_Name($model_name,'_','_'):

            '';

    }

}