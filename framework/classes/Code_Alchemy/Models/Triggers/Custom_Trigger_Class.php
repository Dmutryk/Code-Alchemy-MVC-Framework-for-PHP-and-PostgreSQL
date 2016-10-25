<?php


namespace Code_Alchemy\Models\Triggers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

class Custom_Trigger_Class extends Stringable_Object {

    public function __construct( $model_name, $trigger_type ){

        $this->string_representation = new Namespace_Guess()."\\Models\\Triggers\\".new CamelCase_Name($trigger_type,'_','_').

            "\\".new CamelCase_Name($model_name,'_','_');

    }

}