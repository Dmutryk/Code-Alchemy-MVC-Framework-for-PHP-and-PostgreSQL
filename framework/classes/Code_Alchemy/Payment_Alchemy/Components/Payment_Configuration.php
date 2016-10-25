<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 8:00 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Components;
use Code_Alchemy\Payment_Alchemy\Helpers\Payment_Configuration_File;


/**
 * Class Payment_Configuration
 * @package Code_Alchemy\Payment_Alchemy\Components
 *
 * A Payment service Configuration
 */
class Payment_Configuration extends Payment_Component{

    /**
     * @var array
     */
    private $configuration = null;

    /**
     * Payment_Configuration constructor
     */
    public function __construct(){

        $this->configuration =

            (new Payment_Configuration_File())->find("configuration");

    }

    /**
     * @return array
     */
    public function configuration(){

        return $this->configuration;

    }
}