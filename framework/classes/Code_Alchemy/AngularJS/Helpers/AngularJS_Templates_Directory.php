<?php


namespace Code_Alchemy\AngularJS\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;

class AngularJS_Templates_Directory extends Stringable_Object{

    /**
     * @param string $subdirectory
     */
    public function __construct( $subdirectory = ''){

        $this->string_representation = (string) new Code_Alchemy_Root_Path()."/templates/angularjs/$subdirectory";

    }
}