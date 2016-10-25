<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/27/15
 * Time: 8:38 PM
 */

namespace Code_Alchemy\Database\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Extract_Table_Name_from_SQL_Query
 * @package Code_Alchemy\Database\Helpers
 *
 * Extracts the table name from a SQL Query
 */
class Extract_Table_Name_from_SQL_Query extends Stringable_Object{

    private $regex = array(

        '/\bfrom\b\s*(\w+)/i',

        '/\bupdate\b\s*(\w+)/i',

        '/describe\s(\w+)/i'

    );

    /**
     * @param string $query from which to extract
     *
     */
    public function __construct( $query ){

        // by default
        $table_name = '';

        foreach ( $this->regex as $regex )

            if ( preg_match($regex,$query,$hits)){

                $table_name = $hits[1];

                break;

            }

        $this->string_representation = $table_name;
    }
}