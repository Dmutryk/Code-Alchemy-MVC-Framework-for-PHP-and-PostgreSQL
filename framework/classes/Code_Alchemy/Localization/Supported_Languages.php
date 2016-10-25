<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/26/15
 * Time: 4:38 PM
 */

namespace Code_Alchemy\Localization;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Supported_Languages
 * @package Code_Alchemy\Localization
 *
 * Gets an array of supported languages
 */
class Supported_Languages extends Array_Representable_Object{

    public function __construct(){

        $this->array_values = array('en','es');

    }
}