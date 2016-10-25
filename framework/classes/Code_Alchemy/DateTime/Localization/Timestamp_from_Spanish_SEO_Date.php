<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/3/15
 * Time: 9:53 PM
 */

namespace Code_Alchemy\DateTime\Localization;


use Code_Alchemy\Core\Integer_Value;

/**
 * Class Timestamp_from_Spanish_SEO_Date
 * @package Code_Alchemy\DateTime\Localization
 *
 * Gets a timestamp from a Spanish SEO Date
 */
class Timestamp_from_Spanish_SEO_Date extends Integer_Value{

    public function __construct( $spanish_seo_date ){

        $parts = explode('-',$spanish_seo_date);

        $this->integer_value = strtotime(

            $parts[1]."-".(new Month_Number_from_Spanish_Month($parts[0]))->int_value()
        ."-01 00:00:00"

        );
    }
}