<?php


namespace Code_Alchemy\Templates;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Views\Helpers\Handlebars_Engine;
use Handlebars\Handlebars;

class Web_Snippet extends Stringable_Object {

    public function __construct( $template_name, array $data ){

        $template_path = (string) new Template_Webroot_Path($template_name.".handlebars");

        if ( ! file_exists( $template_path) ){

            \FB::warn("$template_path; No such template exists in application");

            $source_path = (string) new Code_Alchemy_Root_Path()."/templates/handlebars/$template_name.handlebars";

            if ( $this->is_development() && file_exists( $source_path ) && @copy( $source_path,$template_path))

                \FB::info("$template_name: Successfully added Handlebars template to your application");
        }


        $this->string_representation = (new Handlebars_Engine())

            ->render( $template_name, $data);

    }

}