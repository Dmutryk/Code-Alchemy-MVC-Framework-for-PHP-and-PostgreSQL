<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Trigger_After_Insert_Class extends Stringable_Object{

    /**
     * @param string $model_name
     */
    public function __construct( $model_name ){

        $this->string_representation = "\\".new Namespace_Guess()."\\Models\\Triggers\\After_Insert\\". new CamelCase_Name($model_name,'_','_');

    }

}