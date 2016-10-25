<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/15/15
 * Time: 7:15 AM
 */

namespace Code_Alchemy\Multimedia\Images;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\Filesystem\Files\File_Extension;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Regex\Identify_Image_Files_by_Extension;

/**
 * Class Website_Image_Importer
 * @package Code_Alchemy\Multimedia\Images
 *
 * Allows me to import a bunch of website images all at once
 */
class Website_Image_Importer extends Array_Representable_Object{

    /**
     * @var bool true to remove original file after importing
     */
    private $remove_original = false;

    /**
     * @var string resize image when importing
     */
    private $resize_to = '';

    /**
     * @var string set image quality when importing
     */
    private $set_quality_as = '';

    /**
     * @var array of custom values to set
     */
    private $custom_values = [];

    /**
     * @param string $import_directory All images in directory will be imported
     * @param array $options for importing
     */
    public function __construct( $import_directory, array $options = array() ){

        // Set options
        foreach ( $options as $member => $value )

            if ( property_exists($this,$member) )

                $this->$member = $value;


        $imported_files = [];


        // For all images
        foreach ( (new Directory_API( $import_directory))

                      ->directory_listing(true,array(),

                          (string) new Identify_Image_Files_by_Extension())

            as $image_file

        ){

            // set New filename
            $new_image_filename = (string)new Random_Password(20) . "." . new File_Extension($image_file);


            // Set a conversion command
            // Convert and rename
            $conversion_command = (string) new Imagemagick_Conversion_Command($image_file,$import_directory.$new_image_filename,$this->resize_to,$this->set_quality_as);

            // Expose to user
            if ( ! $this->conversion_command && $conversion_command)

                $this->conversion_command = $conversion_command;

            // If we have a conversion
            if ( $conversion_command )

                shell_exec($conversion_command);

            else

                rename($image_file,$import_directory.$new_image_filename);


            $arr =   array(

                'image_filename' => $new_image_filename,

                'title' => (string)new \file_basename_for(array_pop(explode('/', $image_file))),

                'description' => 'Website Image imported by Code Alchemy on ' . date('Y-m-d H:i:s')

            );

            // Add Custom Values
            $arr = count($this->custom_values) ? array_merge( $arr,$this->custom_values) : $arr;

            if (

                // As long as file exists
                file_exists($import_directory.$new_image_filename)

                &&

                (new Model('website_image'))

                    ->create_from($arr)->exists

                &&

                rename($import_directory.$new_image_filename,Code_Alchemy_Framework::instance()->webroot()."/images/website_image/$new_image_filename")

            )

            $imported_files[] = $image_file;

            if ( $this->remove_original ) unlink( $image_file );

        }

        $this->successfully_imported = $imported_files;

    }

}