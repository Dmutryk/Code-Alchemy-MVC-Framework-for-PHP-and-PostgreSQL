<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 7:51 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Components;


/**
 * Class Payment
 * @package Code_Alchemy\Payment_Alchemy\Components
 *
 * Represents a single Payment
 */
class Payment extends Payment_Component{

    /**
     * @param array $payment_values
     */
    public function __construct( array $payment_values = array() ){

        parent::__construct( $payment_values );

    }
}