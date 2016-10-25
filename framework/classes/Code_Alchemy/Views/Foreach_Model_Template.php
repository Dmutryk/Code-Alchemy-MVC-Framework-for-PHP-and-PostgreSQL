<?php


namespace Code_Alchemy\Views;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Views\Helpers\Handlebars_Engine;


/**
 * Class Foreach_Model_Template
 * @package Code_Alchemy\Views
 *
 * Used to render an array of Models using the same template
 */
class Foreach_Model_Template extends Stringable_Object {

    /**
     * @param string $handlebars_template_name
     * @param array $models to render
     */
    public function __construct( $handlebars_template_name, array $models ){

        foreach ( $models as $model )

            $this->string_representation .= (new Handlebars_Engine())->render($handlebars_template_name,$model);

    }

}