<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/20/15
 * Time: 12:29 PM
 */

namespace Code_Alchemy\Multimedia\Images;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Image_Resize_To_Noshrink
 * @package Code_Alchemy\Multimedia\Images
 *
 * Sets image resize when shrink is not allowed
 */
class Image_Resize_To_Noenlarge extends Stringable_Object{

    /**
     * @param string $image_full_path
     * @param string $proposed_resize_to
     */
    public function __construct( $image_full_path, $proposed_resize_to ){

        $dims = explode('x',$proposed_resize_to);

        $size = getimagesize($image_full_path);

        $this->string_representation = $size[0] >= (int)$dims[0] ?

            $proposed_resize_to : '';

    }
}