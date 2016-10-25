<?php


namespace Code_Alchemy\Database\SQL;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\Database;

class Insert_SQL extends Composed_SQL {

    public function __construct( $table, array $columns, array $values){


        $fields = '';

        $vals = '';

        foreach( $values as $name=>$value ){

            if (in_array($name,$columns)){

                // Skip empty ids
                if ( preg_match('/([a-zA-Z_0-9]+)_id/',$name) && ! $value )

                    continue;


            $fields .= $fields? ",`$name`": "`$name`";

                // For boolean
                if ( is_bool($value)) $value = $value ? 'TRUE': 'FALSE';


                $value = (new Database())->real_escape_string($value, $table);

            $vals .= $vals? ",'$value'": "'$value'";


            } else {


            }

        }

        $sql = "INSERT INTO `$table` ($fields) VALUES($vals)";


        //\FB::info(get_called_class().": Insert SQL is $sql");

        $this->string_representation = $sql;


    }

}
