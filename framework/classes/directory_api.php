<?php
namespace Code_Alchemy\apis;


use Code_Alchemy\components\file_directory;
use Code_Alchemy\Core\Webroot;

/**
 * Class directory_api provides a super simple way to fetch the contents of
 * a directory, and access the files as an array in PHP
 *
 * @package Code_Alchemy\APIs
 */
class directory_api {

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
     * @var \xobjects\components\file_directory object
     */
    private $directory = null;


    /**
     * @param string $path to search
     */
    public function __construct( $path ){

        // Set relative path
        $this->relative_path = "$path";



        // Set root
        $this->root = (substr($path,0,1) == '/')? $path:  new Webroot()."/".$path;

        if ( ! file_exists( $this->root ) || ! is_dir( $this->root ))

            $this->error = "$this->relative_path: Doesn't exist, or not a directory";

        else

            // Fetch Directory
            $this->directory = new file_directory( $this->root );

    }

    /**
     * @param bool $include_path to include path when returning files
     * @return array of files in directory
     */
    public function directory_listing( $include_path = false){

        $listing = array();

        $prefix = $include_path ? $this->relative_path:'';

        // if an error
        if ( ! $this->directory || strlen( $this->error))

        {
            //$listing['error'] = $this->error;
        }

        else

            while ( $file = $this->directory->next(true))

                $listing[] = $prefix.$file;

            sort( $listing, SORT_NATURAL );

        return $listing;

    }

}