<?php

namespace Code_Alchemy\Vendors\Widen;

use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Filesystem\Files\File_Extension;
use Code_Alchemy\Multimedia\Document_Thumbnail;
use Code_Alchemy\Security\Officer;


/**
 *
 * Component to send back to client information about last uploaded file
 *
 * User: "David Owen Greenberg" <owen.david.us@gmail.com>
 * Date: 11/03/13
 * Time: 06:01 PM
 */
class After_Upload extends Array_Representable_Object {

    private $convertibles = array('tif','tiff',  'flv', 'mp4', 'avi','wmv','mov','doc','docx','xls','xlsx','ppt','pptx');

    // do we need a thumbnail?
    private $document_types = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx');

    private $thumbnail_object = null;

    /**
     * @var string Filename
     */
    private $filename = '';

    /**
     * @var bool true if requires conversion
     */
    public  $requires_conversion = false;

    private $conversions = array(
        'flv'=>'mp4',
        'avi'=>'mp4',
        'wmv'=>'mp4',
        'mov'=>'mp4',
        'mp4'=>'ogv',   // new! convert MP4 uploads to Ogg
        'doc'=>'pdf',
        'docx'=>'pdf',
        'xls'=>'pdf',
        'xlsx'=>'pdf',
        'ppt'=>'pdf',
        'pptx'=>'pdf',

    );

    public function __construct( $filename, $original_filename ){

        if ((new Officer())->is_admitted()){

            // get filename from Fine
            $this->filename = $filename;

            $this->original_filename = $original_filename;

            // Set type
            $this->type = (string) new \file_type_for($this->filename);

            // convert to lowercase to allow to caps only extensions
            $this->file_type = strtolower((string) new File_Extension($filename));

            $this->requires_conversion = in_array($this->file_type,$this->convertibles)?'yes':'no';

            // indicates whether the client should be required to crop the image
            $this->requires_crop = $this->result = 'success';


            if ( ! ($this->requires_conversion =='yes') && in_array( $this->file_type, $this->document_types)){

                \FB::log("generating document thumbnail");

                $thumbnail = new Document_Thumbnail($this->filename);

                $this->thumbnail_generated = $thumbnail->success()?'yes':'no';

                $this->thumbnail_object = $thumbnail;

                $this->thumbnail_error = $thumbnail->error;

                \FB::log($thumbnail->thumb_filename());

            }

            // should we use aviary?
            $image_types = array( 'jpeg', 'png', 'gif', 'jpg', 'bmp', 'tif', 'tiff');
            $this->use_aviary = in_array($this->file_type, array_merge($image_types,$this->document_types))?'yes':'no';
            if ( $this->use_aviary == 'yes'){

                // Get the User I am "running as"
                $me = (new Officer())->me();

                $filename = $this->is_document()?$this->thumbnail_filename():$this->filename;

                $this->aviary_filename = '/user_images/'.$me->uuid. "/".$filename;

                // expose User File Id
                $this->aviary_user_file_id =
                $this->aviary_context = $this->is_document()?'content_thumbnail':'content_preedit';
            }

        }
    }

    /**
     * @return string thumbnail filename
     */
    public function thumbnail_filename(){

        return $this->thumbnail_object? $this->thumbnail_object->thumb_filename():'';

    }

    /**
     * @return bool true if this is a document
     */
    private function is_document(){

        return !! in_array( $this->file_type, $this->document_types );

    }

    // get the converted filename
    private function converted_filename(){
        return preg_replace("/\.$this->file_type/",".".$this->conversions[$this->file_type],$this->filename);
    }
}
