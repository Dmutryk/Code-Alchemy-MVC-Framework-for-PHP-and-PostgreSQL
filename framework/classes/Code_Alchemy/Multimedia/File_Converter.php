<?php
namespace Code_Alchemy\Multimedia;

use Code_Alchemy\Filesystem\Files\File_Extension;
use Code_Alchemy\Security\Officer;
use Code_Alchemy\tools\code_tag;

class File_Converter {

    /**
     * @var array of settings used
     */
    private $settings = array();

    /**
     * @var array of file extensions for types to be converted
     */
    private $convertibles = array('tiff','tif',   'flv', 'mp4', 'avi','wmv','mov', 'doc','docx','xls','xlsx','ppt','pptx');

    /**
     * @var string message output from conversion
     */
    public $message = '';

    /**
     * @var bool true if file requires conversion
     */
    public $requires_conversion = false;

    /**
     * @var string result as a token
     */
    public $result = 'success';

    /**
     * @var array of mappings between file types and their converters
     */
    private $converters = array(
        'tif'=>'\bragme\beans\image_converter',
        'tiff'=>'\bragme\beans\image_converter',
        'flv'=>'xo_video_file_converter',
        'avi'=>'xo_video_file_converter',
        'wmv'=>'xo_video_file_converter',
        'mov'=>'xo_video_file_converter',
        'mp4'=>'xo_video_file_converter',
        'doc'=>'\\Code_Alchemy\\Multimedia\\Office_Document_Converter',
        'docx'=>'\\Code_Alchemy\\Multimedia\\Office_Document_Converter',
        'xls'=>'\\Code_Alchemy\\Multimedia\\Office_Document_Converter',
        'xlsx'=>'\\Code_Alchemy\\Multimedia\\Office_Document_Converter',
        'ppt'=>'\\Code_Alchemy\\Multimedia\\Office_Document_Converter',
        'pptx'=>'\\Code_Alchemy\\Multimedia\\Office_Document_Converter',
    );

    /**
     * @var string command result from shell
     */
    private $command = '';

    /**
     * @var string error from operation
     */
    public $error = '';

    /**
     * @var string humbnail filename
     */
    public $thumbnail_filename = '';

    /**
     * @param string $filename to be converted
     */
    public function __construct( $filename ){

        $tag = new code_tag(__FILE__,__LINE__,get_class(),__FUNCTION__);

        $this->filename = $filename;

        $this->settings['filename'] = $this->filename;

        $this->file_type = (string) new File_Extension($filename);

        $this->settings['file_type'] = $this->file_type;

        $this->requires_conversion = !! in_array(strtolower($this->file_type),$this->convertibles);

        $this->settings['requires_conversion'] = $this->requires_conversion;

        $this->result = 'success';

        $this->message = 'Your file has been converted';

        if ($this->requires_conversion){

            $mgr = new Officer();

            // Get the user I am "running as"
            $me  = $mgr->me();

            global $webapp_location;

            $converter_class = $this->converters[strtolower($this->file_type)];

            $this->settings['converter_class'] = $converter_class;

            $converter = new $converter_class(

                // the full path to the file for conversion
                $webapp_location."/user_images/$me->uuid/$this->filename",

                // the target directory to save the converted file
                $webapp_location."/user_images/$me->uuid/",

                $converter_class == '\\Code_Alchemy\\Multimedia\\Office_Document_Converter'?false:new \bm_logger()

            );

            // set flag for conversion
            $flags = 0;

            if ( $converter_class == 'xo_video_file_converter'){

                $defer = setting::create("name='defer_video_conversion'")->value;

                $flags = $defer=='yes'?1:

                    ($defer=='ogv'?5:7);
            }

            $this->result = $converter->convert($flags?$flags:null)?'success':'error';

            $this->error = $converter->error;

            $this->command = get_class($converter) =='\\Code_Alchemy\\Multimedia\\Office_Document_Converter'? $converter->command: new \xo_array($converter->commands);

            if ( $this->result == 'success'){

                if ( get_class($converter) =='\\Code_Alchemy\\Multimedia\\Office_Document_Converter' )

                    $this->filename = preg_replace("/\.$this->file_type$/",".pdf",$this->filename);

                //$this->output = $converter->output;

                // now pre-generate a thumbnail, so Aviary may be used to edit it
                if ( preg_match('/Office_Document/',$converter_class)){

                    $this->use_aviary = 'yes';

                    $thumbnail = new Document_Thumbnail($this->filename);

                    $this->thumbnail_generated = $thumbnail->success()?'yes':'no';

                    $this->aviary_filename = "/user_images/". (new Officer())->me()->uuid . "/". $thumbnail->thumb_filename();

                    $this->thumbnail_filename = $thumbnail->thumb_filename();

                    $this->aviary_context = 'content_thumbnail';

                }

                // if we converted an image..
                if ( preg_match('/image_converter/',$converter_class)){

                    // set the converted filename
                    $this->converted_filename = $converter->new_filename();

                    // we should use aviary
                    $this->use_aviary = 'yes';

                    // set context
                    $this->aviary_context = 'content_preedit';

                    // set editable image
                    $this->aviary_filename = "/user_images/$me->userUUID/".$converter->new_filename();

                }

            }

        } else {

            $this->message = "$this->file_type: No Conversion required";

        }
    }
}
