<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/6/15
 * Time: 12:15 PM
 */

namespace Code_Alchemy\Localization;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Localized_Error
 * @package Code_Alchemy\Localization
 *
 * localizes an error based on system language
 *
 */
class Localized_Error extends Stringable_Object {


    /**
     * @param string $error_text to Localize
     */

    public function __construct( $error_text ){

        $this->string_representation = (new Errors_Configuration())->localize( $error_text );

    }
}