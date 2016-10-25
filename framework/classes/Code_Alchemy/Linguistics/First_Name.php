<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/19/15
 * Time: 3:02 PM
 */

namespace Code_Alchemy\Linguistics;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class First_Name
 * @package Code_Alchemy\Linguistics
 *
 * Extracts the first name from a full-name string
 */
class First_Name extends Stringable_Object{

    /**
     * @param string $full_name
     */
    public function __construct( $full_name){

        $names = explode(' ',preg_replace('/\s+/',' ',trim($full_name)));

        switch ( count( $names ) ){

            case 1:

                $this->string_representation = $names[0];

                break;

            case 2:

                $this->string_representation = $names[0];

                break;

            case 3:

                $this->string_representation = $names[0];

                break;

            case 4:

                $this->string_representation = $names[0]. ' '.$names[1];

                break;

            case 5:

                $this->string_representation = $names[0]. ' '.$names[1];


                break;

            default:

                $this->string_representation = $full_name;


        }

    }

}