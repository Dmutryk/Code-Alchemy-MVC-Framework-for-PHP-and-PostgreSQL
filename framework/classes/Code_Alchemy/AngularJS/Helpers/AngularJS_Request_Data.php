<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/7/15
 * Time: 9:17 PM
 */

namespace Code_Alchemy\AngularJS\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class AngularJS_Request_Data
 * @package Code_Alchemy\AngularJS\Helpers
 *
 * Gets AngularJS Request Data
 */
class AngularJS_Request_Data extends Array_Representable_Object{

    public function __construct(){

        $this->array_values =  json_decode(file_get_contents("php://input"),true);

    }
}