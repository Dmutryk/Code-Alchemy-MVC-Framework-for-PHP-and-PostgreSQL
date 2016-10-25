<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 2:42 PM
 */

namespace Code_Alchemy\Text_Operators;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Replace_Text_Chunk_With_String
 * @package Code_Alchemy\Text_Operators
 *
 * Replaces a chunk of one or more lines of text in a string, with another string
 */
class Replace_Text_Chunk_With_String extends Stringable_Object {

    /**
     * @param $original_string
     * @param $regex_start
     * @param $regex_end
     * @param $replacement_str
     */
    public function __construct( $original_string, $regex_start, $regex_end, $replacement_str){

        $result = '';

        $in = false;

        $is_placed = false;

        foreach ( explode(PHP_EOL,$original_string) as $line ){

            if ( preg_match($regex_start,$line,$hits))

                $in = true;

            if ( $in & ! $is_placed){

                $result.= $replacement_str;

                $is_placed = true;

            }

            if ( ! $in )

                $result .= $line. (preg_match('/\n/',$line)? '':"\r\n");


            if ( preg_match($regex_end,$line,$hits)){

                $in = false;

            }



        }

        $this->string_representation = $result;



    }

}