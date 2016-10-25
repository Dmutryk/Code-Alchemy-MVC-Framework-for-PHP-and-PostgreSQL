<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 3/15/16
 * Time: 12:55 AM
 */

namespace Code_Alchemy\Security;


use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Session_Cookie_Domain
 * @package Code_Alchemy\Security
 *
 * Gets session cookie domain if set for app
 */
class Session_Cookie_Domain extends Stringable_Object{

    public function __construct() {

        $this->string_representation = (string) @(new Configuration_File())

            ->find('security')['session-cookie-domain'];
    }
}