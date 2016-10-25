<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 5/1/16
 * Time: 6:01 PM
 */

namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Core\Boolean_Value;
use Code_Alchemy\Core\Configuration_File;

/**
 * Class Is_Production_Host
 * @package Code_Alchemy\Controllers\Helpers
 *
 * Is this a production host?
 */
class Is_Production_Host extends Boolean_Value{

    public function __construct() {

        $HTTP_HOST = $_SERVER['HTTP_HOST'];

        $production_hosts = (new Configuration_File())->production_hosts();

        $this->boolean_value = !! (in_array($HTTP_HOST,

            $production_hosts

            ));
    }
}