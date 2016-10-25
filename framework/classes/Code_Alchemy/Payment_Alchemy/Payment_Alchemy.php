<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 7:48 PM
 */

namespace Code_Alchemy\Payment_Alchemy;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Payment_Alchemy
 * @package Code_Alchemy\Payment_Alchemy
 *
 * Base class for Payment Alchemy
 */
class Payment_Alchemy extends Alchemist {

    /**
     * @var bool true if debugging
     */
    protected $is_debug = false;

    /**
     * @var string The Last error that occurred from any operation
     */
    protected $last_error = '';

    /**
     * @return string last error
     */
    public function last_error(){

        return $this->last_error;

    }

}