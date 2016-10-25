<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 7:47 PM
 */

namespace Code_Alchemy\Payment_Alchemy;
use Code_Alchemy\Core\Array_Representable_Object;


/**
 * Class Process_Result
 * @package Code_Alchemy\Payment_Alchemy
 *
 * Process result for a payment activity
 *
 */
class Process_Result extends Array_Representable_Object{

    /**
     * Process_Result constructor.
     * @param string $result Pass either "success" or "error" depending what happened
     * @param string $error Pass the error, if any, from the transaction
     * @param array $exposable_members Anything else you want to share with invoker
     */
    public function __construct( $result, $error = '', array $exposable_members ){

        $this->result = $result;

        $this->processing_error = $error;

        foreach ( $exposable_members as $name => $value )

            // Expose to caller via parent class
            $this->$name = $value;


    }

    /**
     * @return string result ('success' or 'error'
     */
    public function result(){ return $this->result; }

    /**
     * @return string error
     */
    public function error(){ return $this->_error; }

}