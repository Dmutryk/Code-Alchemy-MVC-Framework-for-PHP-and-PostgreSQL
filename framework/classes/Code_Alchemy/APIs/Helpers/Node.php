<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/26/15
 * Time: 9:48 PM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Node
 * @package Code_Alchemy\APIs\Helpers
 *
 * The Node is the subservice we want to hit for the API, for
 * example /users or /friends, or /clients etc.
 *
 * By default it's the 2nd part of the Request URI
 */
class Node extends Stringable_Object{

    public function __construct(){

        $this->string_representation = (string) (new REQUEST_URI())->part(2);
    }
}