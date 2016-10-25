<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/20/15
 * Time: 12:18 PM
 */

namespace Code_Alchemy\Multimedia\Images;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Imagemagick_Conversion_Command
 * @package Code_Alchemy\Multimedia\Images
 *
 * Constructs an image conversion command
 */
class Imagemagick_Conversion_Command extends Stringable_Object {

    /**
     * @var bool true to not shrink image below current dimensions
     */
    private $do_not_enlarge  = true;


    /**
     * @param $source_path
     * @param $destination_path
     * @param string $resize_to
     * @param string $set_quality_as
     */
    public function __construct( $source_path, $destination_path, $resize_to = '', $set_quality_as = ''){

        // Set resize to based on do not shrink settings
        if ( $this->do_not_enlarge )

            $resize_to = (string) new Image_Resize_To_Noenlarge($source_path,$resize_to);

        $this->string_representation = ($resize_to || $set_quality_as ) ?

            "convert ". ($resize_to ? "-resize $resize_to":'').

            ($set_quality_as ? " -quality $set_quality_as " :'').

            "\"$source_path\" \"$destination_path\""

            :'';
    }
}