<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/14/15
 * Time: 1:42 PM
 */

namespace Code_Alchemy\Database\Adapters\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Postgres_Backtick_Filter
 * @package Code_Alchemy\Database\Adapters\Helpers
 *
 * A filter to strip away back ticks from a Postgres Query
 */
class Postgres_Backtick_Filter extends Stringable_Object{

    public function __construct( $query ) {

        $this->string_representation = (string) str_replace(array("`",chr(96)),array('"','"'), $query);

    }

}