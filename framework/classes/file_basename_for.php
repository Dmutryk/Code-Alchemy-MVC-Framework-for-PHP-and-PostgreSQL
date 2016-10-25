<?php
/**
 * User: "David Owen Greenberg" <code@x-objects.org>
 * Date: 12/02/13
 * Time: 05:05 PM
 */
class file_basename_for {

    /**
     * @var string the basename (calculated at runtime)
     */
    private $basename = '';

    /**
     * @param string $filename to extract basename
     */
    public function __construct($filename){

        if ( preg_match( '/(.+)\.([a-z|A-Z|0-9|_]+)/',$filename,$hits)){
            $this->basename = $hits[1];

        }

    }


    /**
     * @return string the basename afte rbeing calculated
     */
    public function __toString(){
        return $this->basename;
    }
}
