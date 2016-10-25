<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/12/15
 * Time: 12:50 PM
 */

namespace Code_Alchemy\Text_Operators;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Insert_Line_Break_After_Char
 * @package Code_Alchemy\Text_Operators
 *
 * Insert a line break after each instance of a character
 */
class Insert_Line_Break_After_Char extends Stringable_Object{

    /**
     * @param string $char
     * @param string $original_text
     */
    public function __construct(  $char, $original_text ){

        $this->string_representation = preg_replace("/$char/","$char\r\n",$original_text);

    }

}