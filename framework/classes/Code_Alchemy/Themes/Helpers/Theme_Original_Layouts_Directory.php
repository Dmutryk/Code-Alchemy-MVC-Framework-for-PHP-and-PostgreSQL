<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 3:13 PM
 */

namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Theme_Name_Guess;

/**
 * Class Theme_Original_Layouts_Directory
 * @package Code_Alchemy\Themes\Helpers
 *
 * Original layouts directory for theme
 */
class Theme_Original_Layouts_Directory extends Stringable_Object{

    public function __construct(){

        $this->string_representation =

            Code_Alchemy_Framework::instance()->webroot()."/themes/".

            new Theme_Name_Guess()."/original-layouts/";

    }
}