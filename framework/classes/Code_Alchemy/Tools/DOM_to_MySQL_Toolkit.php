<?php


namespace Code_Alchemy\Tools;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\Filesystem\Files\File_Extension;

/**
 * Class DOM_to_MySQL_Toolkit
 * @package Code_Alchemy\Tools
 *
 * This tool is handy for migrating (scraping) data from HTML or XML
 * documents, into a mySQL database
 */
class DOM_to_MySQL_Toolkit extends Array_Representable_Object {

    public function __construct( array $options = array()){

        $this->signature = 'Code Alchemy DOM to MySQL Toolkit, v1.0.0.';

        $this->processed_types = array('html','htm','xml');

        $this->use_recursion = true;

        $this->processed_directories = array();

        $this->processed_files = array();

    }

    /**
     * Process all eligible files within a directory
     * @param string $directory_path
     * @param array $exclusions when searching directory
     */
    public function process_directory( $directory_path, $exclusions = array() ){

        $this->array_values['processed_directories'][] = $directory_path;

        $dir = new Directory_API( $directory_path );

        foreach ( $dir->directory_listing(true,$exclusions) as $file )

            // if file is eligible...
            if ( in_array( (string) new File_Extension($file),$this->processed_types))

                $this->array_values['processed_files'][] = $file;

            // recursion if allowed
            elseif ( $this->use_recursion && is_dir( $file ) )

                $this->process_directory( $file, $exclusions );

    }

}