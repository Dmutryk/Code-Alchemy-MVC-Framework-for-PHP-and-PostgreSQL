<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/3/16
 * Time: 12:50 PM
 */

namespace Code_Alchemy\Pattern_Matching;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Keyword_Space_Contains_Keyword
 * @package Code_Alchemy\Pattern_Matching
 *
 * Set to true, when the given Keyword Space contains at least one of the given keywords
 */
class Keyword_Space_Contains_Keyword extends Boolean_Value{

    /**
     * Keyword_Space_Contains_Keyword constructor.
     * @param $keyword_space
     * @param array $keywords
     */
    public function __construct( $keyword_space, array $keywords ) {

        $contains = false;

        foreach ( $keywords as $keyword ){

            if ( preg_match("/$keyword/i",$keyword_space)){

                $contains = true;

                break;
            }

        }

        $this->boolean_value = $contains;

    }
}