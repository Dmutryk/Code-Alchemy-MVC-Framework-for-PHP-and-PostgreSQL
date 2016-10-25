<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/18/15
 * Time: 11:36 PM
 */

namespace Code_Alchemy\Localization;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Pluralize_in_Spanish
 * @package Code_Alchemy\Localization
 *
 * Pluralizes a word in Spanish
 */
class Pluralize_in_Spanish extends Stringable_Object{

    /**
     * @param string $word
     * @param bool|false $make_conditional use parentheses to make conditional
     */
    public function __construct( $word, $make_conditional = false ){

        $suffix = $this->suffix( $word );

        $this->string_representation = $make_conditional ? "$word($suffix)":  "$word$suffix";

    }

    private function suffix( $word ){

        $suffix = 's';

        // Get last letter
        $last_letter = substr($word,(strlen($word)-1),1);

        switch ( $last_letter ){

            case 'd':

                $suffix = 'es';

                break;
        }

        return $suffix;

    }

}