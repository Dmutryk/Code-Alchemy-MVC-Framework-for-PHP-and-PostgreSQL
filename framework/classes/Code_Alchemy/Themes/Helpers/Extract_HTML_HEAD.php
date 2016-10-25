<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 1:39 PM
 */

namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Extract_HTML_HEAD
 * @package Code_Alchemy\Themes\Helpers
 *
 * Extracts the HTML HEAD from a string of html
 */
class Extract_HTML_HEAD extends Stringable_Object {

    /**
     * @param string $html_string
     */
    public function __construct( $html_string ){

        $head = '';

        $in = false;

        foreach ( explode(PHP_EOL,$html_string) as $line ){

            if ( preg_match('/<head>/',$line,$hits))

                $in = true;

            if ( $in ) $head .= $line ."\r\n";

            if ( preg_match('/<\/head>/',$line,$hits)){

                $in = false;

                break;

            }



        }

        $this->string_representation = $head;


    }

}