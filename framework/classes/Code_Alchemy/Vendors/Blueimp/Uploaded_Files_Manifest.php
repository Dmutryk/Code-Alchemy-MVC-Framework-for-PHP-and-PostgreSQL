<?php


namespace Code_Alchemy\Vendors\Blueimp;


use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\JSON\JSON_File;

class Uploaded_Files_Manifest extends JSON_File {

    /**
     * @var string Manifest directory
     */
    private $manifest_directory = '';

    /**
     * Create a new instance
     */
    public function __construct( $manifest_directory ){

        // Save for later use...
        $this->manifest_directory = $manifest_directory;

        parent::__construct( array(

            // Set path
            'file_path' => "$manifest_directory/upload-manifest.json",

            // Set template
            'template_file' => '/templates/JSON/upload-manifest.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            // Automatically load file
            'auto_load'=>true

        ));



    }

    /**
     * @param string $filename
     */
    public function remove_file( $filename ){

        if ( ( $manifest = @$this->find('uploads')[$filename])){

            // Remove checksum
            $this->prune_selected('md5-checksums',array(

                $manifest['md5-hash']

            ))->update();

            // Remove manifest
            $this->prune_selected('uploads',array(

                $filename

            ))->update();

        }
    }

    /**
     * @param $filename
     * @return array|mixed|null
     */
    public function manifest_for( $filename ){

        $manifest = array();

        if ( isset( $this->find( 'uploads')[ $filename ]  ) )

            $manifest = $this->find('uploads')[ $filename ];

        return $manifest;

    }

    /**
     * After loading the file
     */
    protected function after_load(){

        $current_files = (new Directory_API($this->manifest_directory))

                      ->directory_listing(false,array('upload-manifest.json'));

        $pruned_files = array();

        // Prune out deleted files
        $uploades = $this->find('uploads');

        foreach ($uploades as $filename=>$data )

            // if file doesn't exist on disk
            if ( ! in_array($filename,$current_files)){

                if ( $this->is_development() )

                    \FB::info(get_called_class().": $filename doesn't exist in directory, and will be pruned from Manifest");

                // Prune it from the list
                $pruned_files[] = $filename;


            }

        // If we got something
        if ( count( $pruned_files ))

            // Now remove the prunes
            $this->prune_selected('uploads',$pruned_files)->update();

        /* rebuild hashes

        $md5s = array();

        foreach (  $this->find('md5-checksums') as $checksum ){

            $exists = false;

            foreach ( $this->find('uploads') as $manifest )

                if ( $checksum == $manifest['md5-hash']){

                    $exists = true;

                    break;
                }

            if ( $exists ) $md5s[] = $checksum;

        }

        $this->set('md5-checksums',$md5s)->update();

        */

    }

    /**
     * @param $md5_hash
     * @return bool true if an upload hash already exists
     */
    public function hash_exists( $md5_hash ){

        $m = $this->find('md5-checksums');

        return !! in_array($md5_hash, $m);

    }

    /**
     * Get files as array
     */
    public function files_as_array(){

        $array = array();

        foreach( $this->find('uploads') as $filename =>$data )

            $array[] = array_merge(array("filename"=>$filename),$data);

        return $array;

    }

}