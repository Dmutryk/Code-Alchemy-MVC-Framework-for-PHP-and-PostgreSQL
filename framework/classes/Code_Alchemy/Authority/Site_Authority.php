<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/27/16
 * Time: 7:52 PM
 */

namespace Code_Alchemy\Authority;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Security\Officer;

/**
 * Class Site_Authority
 * @package Code_Alchemy\Authority
 *
 * The Site Authority is responsible for authorizing access, through answering such questions as:
 *
 * 1) Is the current User an administrative User?
 */
class Site_Authority extends Alchemist{

    private $local_authority = null;

    public function __construct() {

        // Give app a chance to declare their own Local Authority
        $local_authority_class = (string) new Namespace_Guess().  @(new Configuration_File())->find('authority')['authority-class'];

        if ( $local_authority_class && class_exists($local_authority_class))

            $this->local_authority = new $local_authority_class;

    }

    /**
     * @return bool true if this is an administrative User
     */
    public function is_administrative_user(){

        return
            $this->is_locally_declared_admin() ||

            (new Officer())->me()->type == 'admin';

    }

    /**
     * @return bool true if locally declared admin
     */
    private function is_locally_declared_admin(){

        return $this->local_authority && $this->local_authority->is_administrative_user();

    }
}