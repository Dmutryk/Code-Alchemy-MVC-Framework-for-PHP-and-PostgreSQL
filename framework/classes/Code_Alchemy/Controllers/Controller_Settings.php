<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/27/15
 * Time: 12:49 AM
 */

namespace Code_Alchemy\Controllers;


use Code_Alchemy\Core\Array_Object;

/**
 * Class Controller_Settings
 * @package Code_Alchemy\Controllers
 *
 * Settings for Controllers
 */
class Controller_Settings extends Array_Object{

    /**
     * Controller_Settings constructor.
     * @param array $members
     */
    public function __construct(array $members) { parent::__construct($members); }
}