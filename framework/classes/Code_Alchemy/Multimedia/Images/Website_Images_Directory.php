<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/23/15
 * Time: 9:36 AM
 */

namespace Code_Alchemy\Multimedia\Images;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Core\Webroot;

/**
 * Class Website_Images_Directory
 * @package Code_Alchemy\Multimedia\Images
 *
 * Directory where website images are stored
 */
class Website_Images_Directory extends Stringable_Object{

    public function __construct(){

        $this->string_representation = new Webroot()."/images/website_image/";

    }
}