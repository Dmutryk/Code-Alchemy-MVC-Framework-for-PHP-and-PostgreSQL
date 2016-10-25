<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/12/15
 * Time: 12:40 PM
 */

namespace Code_Alchemy\Themes\Lists;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\Helpers\Theme_Name_Guess;

/**
 * Class Theme_Head_Files
 * @package Code_Alchemy\Themes\Lists
 *
 * List all theme head files, full paths for each one
 */
class Theme_Head_Files extends Array_Representable_Object{

    public function __construct(){

        $this->array_values = (new Directory_API(

            Code_Alchemy_Framework::instance()->webroot()."/app/views/components/".

            new Theme_Name_Guess()

        ))->directory_listing(true,array('head-common.php'),'/head\-/');

    }
}