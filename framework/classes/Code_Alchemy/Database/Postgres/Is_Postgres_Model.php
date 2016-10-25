<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/23/15
 * Time: 10:50 AM
 */

namespace Code_Alchemy\Database\Postgres;


use Code_Alchemy\Core\Boolean_Value;
use Code_Alchemy\Database\Database_Configuration_File;
use Code_Alchemy\Models\Model_Configuration;

/**
 * Class Is_Postgres_Model
 * @package Code_Alchemy\Database\Postgres
 *
 * is given model postgres?
 */
class Is_Postgres_Model extends Boolean_Value {

    /**
     * @param string $model_name to check
     */
    public function __construct( $model_name ){

        $this->boolean_value = !! (

        (new Database_Configuration_File())->default_connection_type() == 'pgsql'

            ||

        (new Model_Configuration())->model_for($model_name)['connection'] == 'pgsql'

        );

    }
}