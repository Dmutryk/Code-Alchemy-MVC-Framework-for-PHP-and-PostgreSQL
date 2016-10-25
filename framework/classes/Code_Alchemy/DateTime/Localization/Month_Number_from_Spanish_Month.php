<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/3/15
 * Time: 9:55 PM
 */

namespace Code_Alchemy\DateTime\Localization;


use Code_Alchemy\Core\Integer_Value;

/**
 * Class Month_Number_from_Spanish_Month
 * @package Code_Alchemy\DateTime\Localization
 *
 * Gets a Month number from a Spanish Month
 */
class Month_Number_from_Spanish_Month extends Integer_Value{

    public function __construct( $spanish_month_name ){

        switch( strtolower($spanish_month_name )){

            case 'enero': $this->integer_value = 1; break;
            case 'febrero': $this->integer_value = 2; break;
            case 'marzo': $this->integer_value = 3; break;
            case 'abril': $this->integer_value = 4; break;
            case 'mayo': $this->integer_value = 5; break;
            case 'junio': $this->integer_value = 6; break;
            case 'julio': $this->integer_value = 7; break;
            case 'agosto': $this->integer_value = 8; break;
            case 'septiembre': $this->integer_value = 9; break;
            case 'octubre': $this->integer_value = 10; break;
            case 'noviembre': $this->integer_value = 11; break;
            case 'diciembre': $this->integer_value = 12; break;
        }
    }
}