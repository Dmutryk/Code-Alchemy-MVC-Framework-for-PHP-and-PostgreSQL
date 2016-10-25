<?php


namespace Code_Alchemy\Filesystem;


class Specified_File {

    /**
     * @var string File Full Path
     */
    protected $file_full_path = '';

    /**
     * @param string $file_full_path of file
     */
    public function __construct( $file_full_path ){

        $this->file_full_path = $file_full_path;

    }

    /**
     * Require the file once
     */
    public function require_file_once(){

        if ( file_exists($this->file_full_path) )

            require_once $this->file_full_path;

    }

}