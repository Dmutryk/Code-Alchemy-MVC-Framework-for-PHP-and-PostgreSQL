<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/20/15
 * Time: 2:52 PM
 */

namespace Code_Alchemy\Regex;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Identify_Image_Files_by_Extension
 * @package Code_Alchemy\Regex
 *
 * Regex to identify image files via their extension
 */
class Identify_Image_Files_by_Extension extends Stringable_Object{

    public function __construct(){

        $this->string_representation =

            '/(\.svg|\.png|\.jpg|\.jpeg|\.tif|\.tiff|\.bmp|\.gif)/i'

        ;
    }
}