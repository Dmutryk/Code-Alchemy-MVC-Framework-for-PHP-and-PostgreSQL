<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/26/15
 * Time: 4:28 PM
 */

namespace Code_Alchemy\Localization;


use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Current_Language
 * @package Code_Alchemy\Localization
 *
 * Gets a string representation of the current language
 */
class Current_Language extends Stringable_Object {

    public function __construct( $default_language = 'en' ){

        $part1 = (new REQUEST_URI())->part(1);

        $this->string_representation =

            $part1 && in_array($part1,(new Supported_Languages())->as_array())

            ? $part1 : $default_language;

    }
}