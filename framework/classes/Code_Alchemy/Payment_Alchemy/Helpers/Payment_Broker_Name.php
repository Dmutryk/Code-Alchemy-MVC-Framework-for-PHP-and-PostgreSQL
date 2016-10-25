<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/4/15
 * Time: 6:40 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

/**
 * Class Payment_Broker_Name
 * @package Code_Alchemy\Payment_Alchemy\Helpers
 *
 * English: Returns the Classname for the installed Payment Broker
 *
 * español: Devuelve el nombre de clase PHP del Broker de Pagos instalado
 *
 */
class Payment_Broker_Name extends Stringable_Object {

    public function __construct(){

        $configuration = (new Payment_Configuration_File())

            ->find("configuration");

        $this->string_representation =

            "\\Payment_Alchemy\\". $configuration['installed-in']."\\".

            (string) $configuration['broker'].'_Payment_Broker';


    }

}