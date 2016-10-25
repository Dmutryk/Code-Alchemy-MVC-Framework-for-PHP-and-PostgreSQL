<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/17/15
 * Time: 10:06 AM
 */

namespace Code_Alchemy\Multimedia\Images;


use Code_Alchemy\Core\Boolean_Value;
use Code_Alchemy\Filesystem\Files\File_Extension;

/**
 * Class Is_Image
 * @package Code_Alchemy\Multimedia\Images
 *
 * Is the given file an image?
 */
class Is_Image extends Boolean_Value{

    public function __construct( $filename ){

        $this->boolean_value = !! ( in_array(strtolower(new File_Extension($filename)),array(

            'png','jpg','gif','jpeg','tif','bmp','nef','svg'

        )));
    }

}