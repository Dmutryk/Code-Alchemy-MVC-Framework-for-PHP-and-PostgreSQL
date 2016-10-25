<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/3/16
 * Time: 3:56 PM
 */

namespace Code_Alchemy\Arrays\Validators;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class First_Array_Contains_Second
 * @package Code_Alchemy\Arrays\Validators
 *
 * Checks if the first array contains all of the second's members
 */
class First_Array_Contains_Second extends Boolean_Value{

    /**
     * @var array of values missing from first array
     */
    private $missing_values = array();

    /**
     * First_Array_Contains_Second constructor.
     * @param array $first
     * @param array $second
     * @param bool $is_case_sensitive
     */
    public function __construct( array $first, array $second, $is_case_sensitive = true ){

        $contains_second = true;

        if ( ! $is_case_sensitive ){

            $first = array_map('strtolower',$first);

            $second = array_map('strtolower',$second);
        }

        foreach ( $second as $value ){

            if ( ! in_array($value,$first)){

                $contains_second = false;

                $this->missing_values[] = $value;

            }

        }


        $this->boolean_value = $contains_second;

    }

    /**
     * @return array of missing values
     */
    public function missing_values(){

        return $this->missing_values;

    }

}