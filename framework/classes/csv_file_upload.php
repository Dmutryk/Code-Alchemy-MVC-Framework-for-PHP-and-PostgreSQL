<?php
/**
 * Project:
 * Module:
 * Component:
 * Description:
 * Author:
 * Copyright:
 */

namespace Code_Alchemy;


use Code_Alchemy\Core\Random_Password;

class csv_file_upload {

    /**
     * @var string name of FILES member containing uploaded file
     */
    private $name = '';

    /**
     * @var string publicly explosed error
     */
    public $upload_error = '';

    /**
     * @var bool result of the upload process
     */
    private $result = true;

    /**
     * @var string the directory into which to upload the actual file
     */
    private $upload_dir = '';

    /**
     * @var null File handle for performing reads on the original file
     */
    private $file_handle = null;

    /**
     * @var string the name of the uploaded file, after being moved
     */
    private $file_name = '';


    public function __construct( $name ){

        $this->name = $name;

        global $webapp_location;

        $this->upload_dir = $webapp_location. "/app/temp";

        // make sure the directory exists
        if (! file_exists( $this->upload_dir))
            mkdir($this->upload_dir,0777);

    }

    /**
     * Perform any pending uploads for the presented CSV file
     */
    public function upload(){

        $name = isset( $_FILES[$this->name]['name'])?$_FILES[$this->name]['name']:'';

        if ( ! $name ) {

            $this->result = false;

            $this->upload_error = 'No upload file name was specified';

        } else {

            $ext = @end(explode('.', $_FILES[$this->name]['name']));

            if(strtolower($ext) != "csv"){

                $this->result = false;

                $this->upload_error = "Please upload a CSV file";

            } else {

                $this->file_name = (string)new Random_Password(5).$_FILES[$this->name]['name'];

                // move the file
                $result = move_uploaded_file($_FILES[$this->name]['tmp_name'],$this->upload_dir."/".$this->file_name);

                if ( ! $result ){

                    $this->result = false;

                    $this->upload_error = 'Unable to upload file, please check permissions';

                }
            }
        }
    }

    public function is_uploaded(){

        return $this->result;

    }

    /**
     * @return string a line from the opened file, or null if no more input
     */
    public function get_line(){

        $handle = $this->get_handle();

        return fgets($handle);

    }

    private function get_handle(){

        if ( ! $this->file_handle )
            $this->file_handle = fopen($this->upload_dir."/".$this->file_name,'r');

        return $this->file_handle;

    }

}