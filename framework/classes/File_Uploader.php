<?php


namespace Code_Alchemy\helpers;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Multimedia\Images\Is_Image;
use Code_Alchemy\Multimedia\Images\Optimize_Image;

/**
 * Class File_Uploader is a quick helper to instantly move an uploaded file
 * into position, and save the result for the Model array operation
 * @package Code_Alchemy\helpers
 */
class File_Uploader extends Array_Representable_Object{

    /**
     * @var bool true to enable debugging
     */
    private $firebug = true;

    /**
     * Instantly move an Uploaded file to its permanent location
     * @param string $field_name to save filename
     * @param string $save_directory to save actual file
     * @param array $array to modify resulting filename
     * @param int $allowed_types to specify which type is allowed
     */
    public function __construct(

        $field_name,

        $save_directory,

        &$array = array(),

        $allowed_types = \file_upload::ALLOW_IMAGE

    ){

        $this->field_name = $field_name;

        $this->save_directory = $save_directory;

        global $webapp_location;


        $uploader = new \file_upload( $field_name,null,$allowed_types );

        $directory = $webapp_location . $save_directory;


        if ( $uploader->move_to($directory)){

            $this->result = 'success';

            // Added for better compatibility with some Ajax file uploaders
            $this->success = true;

            $this->new_name = $uploader->new_name;

            $this->original_filename = $uploader->original_filename;

            $array[ $field_name ] = $uploader->new_name;

            // Is it an image?
            if ((new Is_Image($uploader->new_name))->bool_value()){

                // Optimize it
                new Optimize_Image($uploader->new_name,"$directory/");


            }

        } else {

            $this->result = 'error';

            $this->error = $uploader->error;

        }

    }

}