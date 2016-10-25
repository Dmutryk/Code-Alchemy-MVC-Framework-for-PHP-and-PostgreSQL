<?php


namespace Code_Alchemy\Database\SQL;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Models\Key_Column;

class Find_SQL extends Composed_SQL {

    /**
     * @param $table_name
     * @param $query
     * @param string $comma_substitute
     */
    public function __construct( $table_name, $query, $comma_substitute = '' , $debug = false){

        $SQL = "SELECT * FROM `$table_name` " .
            SQLCreator::getWHEREclause(

                \HumanLanguageQuery::create($query,(string) new Key_Column($table_name),get_called_class(),$comma_substitute)->conditions(),get_called_class(),$comma_substitute,$table_name) . " LIMIT 1";


        $this->string_representation = $SQL;

    }
}