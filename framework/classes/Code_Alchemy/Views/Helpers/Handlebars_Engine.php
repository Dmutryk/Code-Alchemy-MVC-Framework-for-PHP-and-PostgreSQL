<?php


namespace Code_Alchemy\Views\Helpers;


use Code_Alchemy\Core\Code_Alchemy_Framework;

class Handlebars_Engine {

    /**
     * @param null|string $templates_directory
     * @return \Handlebars\Handlebars engine
     */
    private function engine( $templates_directory = null ){

        $templates_directory = $templates_directory ? $templates_directory : Code_Alchemy_Framework::instance()->webroot()."/templates/";

        return new \Handlebars\Handlebars(array(

            'loader' => new \Handlebars\Loader\FilesystemLoader( $templates_directory ),

            'partials_loader' => new \Handlebars\Loader\FilesystemLoader( $templates_directory )

        ));

    }


    /**
     * @param $template_name
     * @param array $model
     * @return string rendered template
     */
    public function render($template_name,array $model){

        return $this->engine()->render($template_name,$model);

    }

}