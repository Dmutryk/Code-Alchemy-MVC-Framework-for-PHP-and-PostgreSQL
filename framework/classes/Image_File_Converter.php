<?php


namespace Code_Alchemy\helpers;

/**
 * Class Image_Converter is a helper to convert an existing image into a new one
 *
 * @package parnassus\helpers
 */
class Image_File_Converter {

    /**
     * @var string New Filename after conversion
     */
    public $new_filename = '';

    /**
     * COnvert an existing image file
     * @param string $original_filename
     * @param string $location_directory
     * @param string $prefix
     * @param string $width
     * @param string $height
     * @param string $quality
     */
    public function __construct( $original_filename, $location_directory, $prefix , $width ='', $height, $quality){

        // Set original File
        $original_file = $location_directory.'/'.$original_filename;

        // Set thumb path
        $thumb_path = $location_directory.'/'.$prefix.$original_filename;

        $shell_command = "convert -resize $width" . "x" . "$height -quality $quality $original_file $thumb_path";


        $result = exec($shell_command,$output,$return_val);

        // If successful
        if ( file_exists($thumb_path) )

            $this->new_filename = $prefix.$original_filename;
    }
}