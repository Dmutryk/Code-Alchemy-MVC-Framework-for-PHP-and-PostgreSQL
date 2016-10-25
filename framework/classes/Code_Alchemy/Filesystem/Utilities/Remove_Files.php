<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/12/15
 * Time: 8:29 PM
 */

namespace Code_Alchemy\Filesystem\Utilities;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Filesystem\Directory_API;

/**
 * Class Remove_Files
 * @package Code_Alchemy\Filesystem\Utilities
 *
 * Utility to remove files
 */
class Remove_Files extends Array_Representable_Object {

    /**
     * @param string $match_pattern for files to be removed
     * @param string $directory to look for files
     */
    public function __construct( $match_pattern, $directory ){

        $removed_files = array();

        foreach ( (new Directory_API($directory))->directory_listing() as $file )

            if ( preg_match($match_pattern,$file) && unlink("$directory$file") )

                $removed_files[] = $file;

        $this->removed_files = $removed_files;


    }
}