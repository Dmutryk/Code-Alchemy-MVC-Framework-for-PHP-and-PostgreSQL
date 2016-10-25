<?php

namespace Code_Alchemy\Vendors\Widen;

/**
 * PHP Server-Side Example for Fine Uploader (traditional endpoint handler).
 * Maintained by Widen Enterprises.
 *
 * This example:
 *  - handles chunked and non-chunked requests
 *  - assumes all upload requests are multipart encoded
 *
 *
 * Follow these steps to get up and running with Fine Uploader in a PHP environment:
 *
 * 1. Setup your client-side code, as documented on http://docs.fineuploader.com.
 *
 * 2. Copy this file and handler.php to your server.
 *
 * 3. Ensure your php.ini file contains appropriate values for
 *    max_input_time, upload_max_filesize and post_max_size.
 *
 * 4. Ensure your "chunks" and "files" folders exist and are writable.
 *    "chunks" is only needed if you have enabled the chunking feature client-side.
 */



use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Multimedia\File_Converter;
use Code_Alchemy\Security\Officer;

class Fine_Uploader {

    private $config = null;

    /**
     * @var bool true to log to firebug
     */
    private $firebug = false;

    /**
     * @param bool $is_document
     * @param bool $is_audio if upload only allows audio
     */
    public function __construct(

        $is_document = false,

        $is_audio = false,

        array $configuration

    ){

        $this->config = $configuration;

        $uploader = new UploadHandler();

        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = $this->get_allowed_types( $is_document, $is_audio );

        // Specify max file size in bytes.
        $uploader->sizeLimit = 100 * 1024 * 1024; // default is 10 MiB

        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = "chunks";

        $method = $_SERVER["REQUEST_METHOD"];

        if ($method == "POST") {

            header("Content-Type: text/plain");

            // get the user
            $mgr = new Officer();

            // Get the user I am "running as"
            $me = $mgr->me();

            if ( ! $me || ! $me->exists ){

                echo json_encode(array( 'logout_redirect'=>'yes','result'=>'error','error'=>
                'You are not logged in, or your session may have expired'));
            } else {


                global $webapp_location;

                $dir = $webapp_location.(string)$this->config['directory'].$me->uuid;

                if ( $this->firebug ) \FB::info($dir);

                $web_path = (string)$this->config['directory']."".$me->uuid;

                if ( ! file_exists($dir))

                    mkdir($dir);

                // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                $result = $uploader->handleUpload($dir);

                // To return a name used for uploaded file you can use the following line.
                $result["uploadName"] = $uploader->getUploadName();
                $result['full_path'] = $web_path. "/".$uploader->getUploadName();
                $result['original_filename'] = $uploader->original_filename;
                $result['external_path'] = 'http://'.$_SERVER['HTTP_HOST'].$result['full_path'];
                $result['web_path'] = $web_path;

                // Handle file conversions
                $after = new After_Upload( $result['uploadName'], $result['original_filename']);

                $result['thumbnail'] = $after->thumbnail_filename();

                $result['requires_conversion'] = $after->requires_conversion;

                if ( $after->requires_conversion =='yes'){

                    $converter = new File_Converter($result['uploadName']);

                    $result['thumbnail'] = $converter->thumbnail_filename;

                }

                $result = array_merge($result,$after->as_array());

                $_SESSION['last_fineupload'] = $uploader->getUploadName();

                $_SESSION['last_fineupload_original'] = $uploader->original_filename;


                echo json_encode($result);

            }

        }
        else {

            header("HTTP/1.0 405 Method Not Allowed");
        }

    }

    /**
     * Get allowed types for this upload
     * @param bool $is_document
     * @param bool $is_audio
     * @return array of allowed types
     */
    private function get_allowed_types( $is_document, $is_audio ){

        if ( $is_audio )

            return array( 'mp3','au','wav','m4u','m4a','aiff','flac','alac','ogg','mp2','aac','amr','wma');

        elseif ( $is_document )

            return array('doc','docx','pdf','xls','xlsx','ppt','pptx');

        else {

            $load_types = $this->config['load-types'];

            if ( $load_types)

                $types = (new Model('setting'))->find("name='$load_types'")->value;

           else {

                // Set the allowed file extensions

                $types = $this->is_video?(string)$this->config->allowed_video_types:(string)$this->config->allowed_types;

            }

            $fileTypes = explode( ',',$types); // Allowed file extensions

            return $fileTypes;
        }
    }


}