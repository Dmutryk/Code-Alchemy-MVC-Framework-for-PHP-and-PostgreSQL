<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/21/15
 * Time: 4:53 PM
 */

namespace Code_Alchemy\Lifestyle;


use Code_Alchemy\Core\Float_Value;

class Body_Mass_Index extends Float_Value {

    /**
     * @param float $weight_in_kg
     * @param int $height_in_cm
     */
    public function __construct( $weight_in_kg, $height_in_cm ){

        // Change height to meters
        $height_in_m = $height_in_cm / 100;

        $this->float_value = $height_in_m ?

            (float)number_format($weight_in_kg / ( $height_in_m*$height_in_m ),2): 0;

    }

}