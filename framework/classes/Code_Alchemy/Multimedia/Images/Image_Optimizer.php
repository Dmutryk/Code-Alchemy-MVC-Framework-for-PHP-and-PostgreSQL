<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/23/15
 * Time: 9:34 AM
 */

namespace Code_Alchemy\Multimedia\Images;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\Core\Webroot;
use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\Regex\Identify_Image_Files_by_Extension;

/**
 * Class Image_Optimizer
 * @package Code_Alchemy\Multimedia\Images
 *
 * Optimizes images for use in the Web
 */
class Image_Optimizer extends Array_Representable_Object {

    public function __construct(){

        // Get all website images
        $website_images = (new Directory_API( (string) new Website_Images_Directory()))

            ->directory_listing(true,array(),(string)new Identify_Image_Files_by_Extension());

        $this->count_images = count( $website_images );

        $eligible_images = array();

        $updated_images = array();

        // get filesizes
        foreach ( $website_images as $website_image_with_path ){

            $size_in_kb = round(filesize($website_image_with_path) / 1024);

            // if eligible
            if ( $size_in_kb > 150 )

                $eligible_images[] = array(

                    'website_image' => $website_image_with_path,

                    'size' => $size_in_kb
                );

        }

        // Get save dir
        $website_images_dir = (string) new Website_Images_Directory();

        // For each eligible image
        foreach ( $eligible_images as $imageset ){

            // Create a tmp name
            $temp_name = (string) new Random_Password(20);

            // convert image setting quality
            shell_exec( (string) new Imagemagick_Conversion_Command(

                $imageset['website_image'],$website_images_dir.$temp_name,'1500x','50'

            ));

            // if tmp file exists
            if ( file_exists( $website_images_dir.$temp_name)){

                // unlink original
                unlink( $imageset['website_image']);

                // Move back to original
                rename($website_images_dir.$temp_name,$imageset['website_image']);

                // Add to list
                $updated_images[]  = $imageset['website_image'];

            }


        }

        $this->count_eligible = count( $eligible_images );

        $this->count_updated = count( $updated_images );

        $this->updated = $updated_images;

        $this->eligible = $eligible_images;

    }
}