<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/27/16
 * Time: 11:43 AM
 */

namespace Code_Alchemy\Payment_Alchemy\Credentials;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Service_Credentials
 * @package Code_Alchemy\Payment_Alchemy\Credentials
 *
 * A set of service Credentials
 */
class Service_Credentials extends Array_Representable_Object{

    /**
     * Service_Credentials constructor.
     * @param array $credentials
     */
    public function __construct( array $credentials ) {

        $this->array_values = $credentials;

    }
}