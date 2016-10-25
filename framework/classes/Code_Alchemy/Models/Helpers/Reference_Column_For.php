<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Model;

class Reference_Column_For extends Stringable_Object{

    /**
     * @param string $model_name to find reference column
     */
    public function __construct( $model_name ){

        $this->string_representation =

            (new Model($model_name))->reference_column();

    }

}