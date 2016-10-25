<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/6/15
 * Time: 10:13 AM
 */

namespace Code_Alchemy\DateTime\Localization;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Spanish_Date
 * @package Code_Alchemy\DateTime\Localization
 *
 * Allows a timestamp to be formatted as a Spanish date
 */
class Spanish_Date extends Stringable_Object {

    /**
     * @param int $timestamp to represent
     * @param string $format to display
     */
    public function __construct( $timestamp, $format ){

        setlocale(LC_TIME, "es_CO.utf8");

        $this->string_representation = (string) strftime($format,$timestamp);

    }
}