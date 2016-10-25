<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 8:51 AM
 */

namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\components\seo_name;
use Code_Alchemy\Core\Stringable_Object;

class Section_Name extends Stringable_Object {

    /**
     * @var int Section Seq. number for when name not found
     */
    private static $section_sequence_number = 1;

    /**
     * @param string $line_of_html to extract
     * @param string $prefix to prepend
     */
    public function __construct( $line_of_html, $prefix ){

        $section_name = "section-".self::$section_sequence_number++;

        // For sections with an Id
        if ( preg_match("/<section.*\s+id=\"([a-zA-Z0-9\s\-\_]+)\"/",$line_of_html,$hits))

            $section_name = $prefix. "-". new seo_name($hits[1]);

        // for sections with a Class
        elseif (preg_match("/<section.*\s+class=\"([a-zA-Z0-9\s\-\_]+)\"/",$line_of_html,$hits))

            $section_name = $prefix. "-".  new seo_name($hits[1]);

        $this->string_representation = $section_name;

    }

}