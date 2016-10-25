<?php


namespace Code_Alchemy\Templates;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Template_Webroot_Path
 * @package Code_Alchemy\Templates
 *
 * Full Path to a handlebars template in the user's webroot
 */
class Template_Webroot_Path extends Stringable_Object{

    /**
     * @param string $template_filename
     */
    public function __construct( $template_filename ){

        $this->string_representation =

            Code_Alchemy_Framework::instance()->webroot()."/templates/$template_filename";

    }

}