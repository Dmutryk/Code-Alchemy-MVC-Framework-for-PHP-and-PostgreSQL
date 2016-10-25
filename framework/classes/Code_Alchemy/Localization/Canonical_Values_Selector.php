<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/26/15
 * Time: 4:27 PM
 */

namespace Code_Alchemy\Localization;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Canonical_Values_Selector
 * @package Code_Alchemy\Localization
 *
 * Selects Canonical Values based on the current language
 */
class Canonical_Values_Selector extends Array_Representable_Object {

    public function __construct( array $values, Current_Language $language ){

        foreach ( $values as $name => $value )

            // if value for current language
        {


            $regex = "/([a-zA-Z0-9\_\-]+)\_$language$/";

            if ( preg_match($regex,$name,$matches) )

                // Add to values
                $values[$matches[1]] = $value;
        }

        // Copy over values to result set
        $this->array_values = $values;
    }

}