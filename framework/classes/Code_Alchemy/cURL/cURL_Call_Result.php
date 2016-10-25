<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 8:18 PM
 */

namespace Code_Alchemy\cURL;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class cURL_Call_Result
 * @package Code_Alchemy\cURL
 *
 * cURL call result
 */
class cURL_Call_Result extends Array_Representable_Object{

    /**
     * cURL_Call_Result constructor.
     * @param array $data
     */
    public function __construct( array $data ){

        foreach ( $data as $name => $value )

            $this->$name = $value;

    }

    /**
     * @return array response
     */
    public function response(){

        return isset( $this->array_values['body'])?

                json_decode($this->array_values['body'],true):

            array();

    }

}