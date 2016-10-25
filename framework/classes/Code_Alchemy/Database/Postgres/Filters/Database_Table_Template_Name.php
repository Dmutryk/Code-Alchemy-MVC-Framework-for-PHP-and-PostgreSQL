<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/27/16
 * Time: 12:49 PM
 */

namespace Code_Alchemy\Database\Postgres\Filters;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\Postgres\State\Is_PostgreSQL;

/**
 * Class Database_Table_Template_Name
 * @package Code_Alchemy\Database\Postgres\Filters
 *
 * Filter DB filter name for use with PostgreSQL
 */
class Database_Table_Template_Name extends Stringable_Object{

    /**
     * Database_Table_Template_Name constructor.
     * @param $template_name
     */
    public function __construct( $template_name ) {

        $this->string_representation = (new Is_PostgreSQL())

            ->bool_value() ? "pg_$template_name": $template_name;

    }
}