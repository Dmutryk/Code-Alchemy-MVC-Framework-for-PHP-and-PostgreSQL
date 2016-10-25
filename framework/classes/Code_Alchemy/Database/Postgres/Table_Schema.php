<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/24/15
 * Time: 4:43 PM
 */

namespace Code_Alchemy\Database\Postgres;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\Database_Configuration_File;

/**
 * Class Table_Schema
 * @package Code_Alchemy\Database\Postgres
 *
 * The PostgresQL table Schema
 */
class Table_Schema extends Stringable_Object {

    public function __construct() {

        $schema = @(new Database_Configuration_File())

            ->find('pgsql')['schema'];

        if ( ! $schema ) $schema = 'public';

        $this->string_representation = $schema;

    }
}