<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/27/16
 * Time: 11:01 AM
 */

namespace Code_Alchemy\Text_Operators;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Token_Mask
 * @package Code_Alchemy\Text_Operators
 *
 * Creates a mask for a token, showing only a part
 */
class Token_Mask extends Stringable_Object {

    /**
     * Token_Mask constructor.
     * @param $token
     * @param int $reveal_count
     * @param bool $is_from_start
     */
    public function __construct( $token, $reveal_count = 2, $is_from_start = true ) {

        $mask = $is_from_start ?

            (substr($token,0,$reveal_count).$this->_mask( strlen( $token) - $reveal_count)):'';

        $this->string_representation = $mask;

    }

    /**
     * @param $length
     * @return string
     */
    private function _mask( $length ){

        $mask = '';

        for($i=0;$i<$length;$i++)

            $mask .= '*';

        return $mask;
    }

}