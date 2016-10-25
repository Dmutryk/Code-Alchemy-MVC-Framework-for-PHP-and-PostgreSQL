<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/3/16
 * Time: 2:58 PM
 */

namespace Code_Alchemy\Text_Operators;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Collapse_Whitespace
 * @package Code_Alchemy\Text_Operators
 *
 * Collapse all whitespace into single spaces
 */
class Collapse_Whitespace extends Stringable_Object{

    /**
     * Collapse_Whitespace constructor.
     * @param $subject
     */
    public function __construct($subject ) {

        $this->string_representation = preg_replace("/[\s\r\n\t]+/"," ",trim($subject));

    }
}