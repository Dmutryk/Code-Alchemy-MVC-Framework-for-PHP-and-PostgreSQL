<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 5:20 PM
 */

namespace Code_Alchemy\Themes\Layouts;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Layout_Content
 * @package Code_Alchemy\Themes\Layouts
 *
 * Layout content, as a string
 */
class Layout_Content extends Stringable_Object{

    public function __construct( $layout_full_path ){

        $this->string_representation = file_get_contents($layout_full_path);

    }
}