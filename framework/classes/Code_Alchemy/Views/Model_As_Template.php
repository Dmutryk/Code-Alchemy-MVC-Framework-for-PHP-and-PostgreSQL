<?php


namespace Code_Alchemy\Views;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Views\Helpers\Handlebars_Engine;

class Model_As_Template extends Stringable_Object {

    public function __construct( array $model, $template_name ){

        $this->string_representation = (string) (new Handlebars_Engine())->render($template_name,$model);

    }

}