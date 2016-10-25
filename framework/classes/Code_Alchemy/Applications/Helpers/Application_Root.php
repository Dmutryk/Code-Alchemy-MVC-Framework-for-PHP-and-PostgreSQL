<?php


namespace Code_Alchemy\Applications\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;

class Application_Root extends Stringable_Object {

    /**
     * @param string $name of application
     */
    public function __construct( $name ){

        $this->string_representation = (string) new Code_Alchemy_Root_Path().
            "/templates/applications/$name";

    }

}