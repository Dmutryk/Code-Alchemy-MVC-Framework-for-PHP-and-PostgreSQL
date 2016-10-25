<?php

namespace Code_Alchemy\Filesystem;


use Code_Alchemy\components\file_directory;
use Code_Alchemy\Core\Code_Alchemy_Framework;

/**
 * Class directory_api provides a super simple way to fetch the contents of
 * a directory, and access the files as an array in PHP
 *
 * @package Code_Alchemy\APIs
 */
class Directory_API {

    /**
     * @var string indicating an error condition
     */
    public $error = '';

    /**
     * @var string root directory
     */
    private $root = '';

    /**
     * @var string relative path
     */
    private $relative_path = '';

    /**
     * @var file_directory object
     */
    private $directory = null;


    /**
     * @param string $path to search
     */
    public function __construct( $path ){

        // Set relative path
        $this->relative_path = "$path";

        // Set root
        $this->root = (substr($path,0,1) == '/')? $path:  Code_Alchemy_Framework::instance()->webroot()."/".$path;

        if ( ! file_exists( $this->root ) || ! is_dir( $this->root ))

            $this->error = "$this->relative_path: Doesn't exist, or not a directory";

        else

            // Fetch Directory
            $this->directory = new file_directory( $this->root );

    }


    /**
     * @param bool|false $include_path
     * @param array $exclusions
     * @param string $include_regex
     * @param string $file_prefix to add to every file returned.  Ignored when $include_path is true
     * @return array
     */
    public function directory_listing(

        $include_path = false,

        $exclusions = array(),

        $include_regex = '',

        $file_prefix = ''

    ){

        $listing = array();

        $prefix = $include_path ? $this->relative_path."/":

            ( $file_prefix ? $file_prefix :'');

        // if an error
        if ( ! $this->directory || strlen( $this->error))

        {
            //$listing['error'] = $this->error;
        }

        else

            while ( $file = $this->directory->next(true))

                // if not excluded
                if ( ! in_array($file,$exclusions) &&
                
                    ( ! $include_regex || preg_match($include_regex,$file))
                )

                    // add it
                    $listing[] = $prefix.$file;

            sort( $listing, SORT_NATURAL );

        return $listing;

    }

}