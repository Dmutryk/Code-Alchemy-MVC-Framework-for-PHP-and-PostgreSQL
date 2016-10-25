<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/13/15
 * Time: 9:39 PM
 */

namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Theme_Name_Guess;

/**
 * Class Theme_Directory
 * @package Code_Alchemy\Themes\Helpers
 *
 * The directory of the current theme
 */
class Theme_Directory extends Stringable_Object{

    public function __construct(){

        $this->string_representation =

            Code_Alchemy_Framework::instance()->webroot()."/themes/".

            new Theme_Name_Guess();

    }

}