<?php

namespace Code_Alchemy\Multimedia;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Filesystem\Files\File_Extension;

/**
 * Class Office_Document_Converter
 * @package Code_Alchemy\Multimedia
 *
 * Component to convert Office documents to PDF
 */
class Office_Document_Converter extends Array_Representable_Object {

    /**
     * @var bool true to send debugging to Firebug
     */
    private $firebug = true;

    /**
     * @var string Filename to convert
     */
    private $filename = '';

    /**
     * @var string New filename
     */
    public $new_filename = '';

    /**
     * @var string type of Document
     */
    private $type = '';

    /**
     * @var string File extension
     */
    private $extension = '';

    /**
     * @var string Save directory
     */
    private $save_dir = '';

    /**
     * @var string command to run
     */
    public $command = '';

    /**
     * Construct a new Component
     * @param string  $filename to convert
     * @param string $save_dir to save result
     * @param bool $firebug to send output
     */
    public function __construct($filename,$save_dir,$firebug = false){

        // Set output
        $this->firebug = $firebug;

        // set Filename
        $this->filename = $this->new_filename = $filename;

        // Set Type
        $this->type = (string) new \file_type_for($filename);

        // Set Extension
        $this->extension = (string) new File_Extension($filename);

        // Set Save dir
        $this->save_dir = $save_dir;

    }

    /**
     * @return bool true if Converted
     */
    public function convert(){

        if ( $this->firebug || $this->is_development() ) \FB::info(get_called_class().": Ready to convert $this->filename");

        $result = false;

        // If not a document
        if ( $this->type != 'document')

            // Set an error
            $this->error = "$this->filename: Not a document";

        else {

            // convert from MS Office
            if ( in_array( $this->extension,array('doc','docx','xls','xlsx','ppt','pptx'))){

                $this->new_filename = preg_replace("/\.$this->extension/",'.pdf',$this->filename);

                $config = (new Configuration_File())->find('libre-office',true);

                if ( ! $config ){


                    $this->error = 'There is no Code Alchemy configuration for libreoffice';

                    \FB::error(get_called_class().": ".$this->error);

                }


                else {

                    $cmd = "env -i ". (string) $config->binary . " --headless --invisible --convert-to pdf --outdir $this->save_dir --nofirststartwizard $this->filename 2>&1";

                    if ( $this->firebug || $this->is_development() ) \FB::info(get_called_class().": Command is $cmd");

                    $this->command = $cmd;

                    $command = new \xo_shell_command($cmd);

                    if ( ! $command->execute())

                        $this->error = $command->output;

                    else {

                        $result = true;

                        $this->output = $command->output;

                        if ( $this->firebug || $this->is_development() ) \FB::info(get_called_class().": Output is $this->output");

                    }
                }
            }
        }

        if ( $this->firebug || $this->is_development() ) \FB::info($this->array_values);

        return $result;
    }
}