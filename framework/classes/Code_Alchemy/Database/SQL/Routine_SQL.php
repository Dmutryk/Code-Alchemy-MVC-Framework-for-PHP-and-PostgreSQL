<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 11:14 PM
 */

namespace Code_Alchemy\Database\SQL;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\SQL\templates\SQL_Template;

/**
 * Class Routine_SQL
 * @package Code_Alchemy\Database\SQL
 *
 * Gets SQL to create a Routine
 */
class Routine_SQL extends Stringable_Object{

    /**
     * Routine_SQL constructor.
     * @param $routine_name
     */
    public function __construct( $routine_name ) {

        $this->string_representation =

            (string)(new SQL_Template('/routines/',$routine_name));

    }
}