<?php


namespace Code_Alchemy\Core;

/**
 * Class Array_Representable_Object
 * @package Code_Alchemy\Core
 *
 * Allows you to package up an array of values, into a flexible Object
 * that can either be used for JSON output, or for other purposes.
 *
 * Works great with JSON example:
 *
 * class My_Result extends Array_Representable_Object
 *
 * new Displayed_JSON_Output( new My_Result( array( 'name' => $value )));
 *
 */
class Array_Representable_Object extends Alchemist{

    /**
     * @var array values that can represent the object
     */
    protected $array_values = array();

    /**
     * @var string pre-hook for as array
     */
    private $as_array_pre_hook = '';

    /**
     * @param array $exclusions to remove from array
     * @return array of resulting values
     */
    public function as_array( array $exclusions = array() ){

        $hook = $this->as_array_pre_hook;

        // Hook allows modification of members just before being returned
        if ( method_exists($this, $hook))

            $this->$hook($exclusions);

        $values = $this->array_values;

        foreach ( $exclusions as $exclude )

            unset( $values[$exclude]);

        return $values;

    }

    /**
     * @param string $hook to set
     */
    protected function set_pre_hook( $hook ){

        $this->as_array_pre_hook = $hook;

    }

    /**
     * Allows me to set members directly, without referencing the array
     * @param string $member
     * @param mixed $value
     */
    public function __set( $member, $value ){

        $this->array_values[ $member ] = $value;

    }

    /**
     * @param array $values
     * @return $this
     */
    public function set_values( array $values ){

        foreach ( $values as $name => $value )

            $this->$name = $value;

        return $this;

    }

    /**
     * Convenience method to fetch a member
     * @param $what
     * @return null
     */
    public function __get( $what ){

        return isset( $this->array_values[$what])? $this->array_values[$what]: null;

    }

    /**
     * Get As an Object
     * @param array $exclusions
     * @return Array_Object
     */
    public function as_object( array $exclusions = array() ){

        return new Array_Object( $this->as_array( $exclusions ) );
    }

    /**
     * @return string representation of Object
     */
    public function __toString(){

        $interior_part = '';

        foreach ( $this->array_values as $name => $value )

            $interior_part .= $interior_part ? ", $name = $value":"$name = $value";

        return "array($interior_part)";

    }


}