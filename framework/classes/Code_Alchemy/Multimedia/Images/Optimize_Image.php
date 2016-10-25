<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/17/15
 * Time: 10:09 AM
 */

namespace Code_Alchemy\Multimedia\Images;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Random_Password;

/**
 * Class Optimize_Image
 * @package Code_Alchemy\Multimedia\Images
 *
 * Optimize an image, replacing original with optimized version
 */
class Optimize_Image extends Alchemist {

    /**
     * @param string $image_filename
     * @param string $image_directory
     */
    public function __construct( $image_filename, $image_directory ){

        if ( $this->is_development() ) \FB::info(get_called_class().": Image $image_filename in directory $image_directory is being optimized");

        $size_in_kb = round(filesize($image_directory.$image_filename) / 1024);

        // if eligible
        if ( $size_in_kb > 150 ){

            // Create a tmp name
            $temp_name = (string) new Random_Password(20);

            // convert image setting quality
            shell_exec( (string) new Imagemagick_Conversion_Command(

                $image_directory.$image_filename,$image_directory.$temp_name,'1500x','50'

            ));

            // if tmp file exists
            if ( file_exists( $image_directory.$temp_name)){

                // unlink original
                unlink( $image_directory.$image_filename);

                // Move back to original
                rename($image_directory.$temp_name,$image_directory.$image_filename);


            }



        }


    }

}