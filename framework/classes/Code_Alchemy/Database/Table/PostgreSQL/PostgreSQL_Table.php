<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 8:56 PM
 */

namespace Code_Alchemy\Database\Table\PostgreSQL;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\CamelCase_Name;

/**
 * Class PostgreSQL_Table
 * @package Code_Alchemy\Database\Table\PostgreSQL
 *
 * A specific PostgreSQL Table
 */
class PostgreSQL_Table extends Array_Representable_Object{

    /**
     * PostgreSQL_Table constructor.
     * @param array $schema_information
     *
     * Schema Information
     */
    public function __construct( array $schema_information ){

        $this->array_values = array_merge($schema_information,array(

            'label' => (string) new CamelCase_Name($schema_information['table_name'],'_',' ')

        ));

    }
}