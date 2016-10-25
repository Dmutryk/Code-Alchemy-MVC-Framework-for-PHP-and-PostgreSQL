<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/23/15
 * Time: 7:44 PM
 */

namespace Testing\Unit_Tests;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Unit_Test
 * @package Testing\Unit_Tests
 *
 * A Unit Test
 */
abstract class Unit_Test extends Alchemist{

    /**
     * @return void
     */
    abstract public function execute();

}