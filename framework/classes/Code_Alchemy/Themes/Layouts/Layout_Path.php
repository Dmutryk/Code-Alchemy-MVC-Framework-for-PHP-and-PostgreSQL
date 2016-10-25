<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 1:31 PM
 */

namespace Code_Alchemy\Themes\Layouts;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Theme_Name_Guess;

/**
 * Class Layout_Path
 * @package Code_Alchemy\Themes\Layouts
 *
 * Gets the layout path from its canonical name
 */
class Layout_Path extends Stringable_Object {

    /**
     * @param string $layout_canonical_name for layout
     */
    public function __construct( $layout_canonical_name ){

        $this->string_representation =

            Code_Alchemy_Framework::instance()->webroot()."/themes/".

            new Theme_Name_Guess()."/$layout_canonical_name";

    }

}