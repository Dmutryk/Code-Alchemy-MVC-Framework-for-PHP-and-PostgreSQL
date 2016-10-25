<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 5/23/16
 * Time: 4:17 PM
 */

namespace Code_Alchemy\Database\SQL\Helpers;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Postgres\Is_Postgres_Model;

/**
 * Class Real_SQL_Value
 * @package Code_Alchemy\Database\SQL\Helpers
 *
 * Gets a REAL SQL Value
 */
class Real_SQL_Value extends Alchemist{

    private $value;

    /**
     * Real_SQL_Value constructor.
     * @param $value
     * @param string $model_name
     */
    public function __construct( $value, $model_name = '') {

        $this->value = $value;


    }

    /**
     * @return mixed
     */
    public function value(){ return $this->value; }
}