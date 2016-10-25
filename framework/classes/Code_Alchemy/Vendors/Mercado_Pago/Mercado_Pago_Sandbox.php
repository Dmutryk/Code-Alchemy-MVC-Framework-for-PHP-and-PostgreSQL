<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/4/15
 * Time: 10:44 AM
 */

namespace Code_Alchemy\Vendors\Mercado_Pago;


/**
 * Class Mercado_Pago_Sandbox
 * @package Code_Alchemy\Vendors\Mercado_Pago
 *
 * Returns a Sandbox edition of the Mercado Pago Processor
 *
 */
class Mercado_Pago_Sandbox extends Mercado_Pago {

    public function __construct(){

        parent::__construct('4054665354642867','R0JXo8mH07CkSVGXEkC0yNOmPi5fmNmP');

        // Set sandbox Model
        $this->sandbox_mode(true);

    }

}