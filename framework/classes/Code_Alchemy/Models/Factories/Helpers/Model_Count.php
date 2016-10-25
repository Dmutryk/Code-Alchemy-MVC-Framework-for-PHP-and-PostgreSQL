<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/15/15
 * Time: 6:10 PM
 */

namespace Code_Alchemy\Models\Factories\Helpers;


use Code_Alchemy\Core\Integer_Value;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Database\Result\Fetch_Associative_Values;
use Code_Alchemy\Database\Result\Query_Result;
use Code_Alchemy\Models\Model_Configuration;

/**
 * Class Model_Count
 * @package Code_Alchemy\Models\Factories\Helpers
 *
 * Represents a COunt of Models as an Integer Value
 */
class Model_Count extends Integer_Value {

    public function __construct( $model_name, $query ){

        $this->integer_value = 0;

        // construct a sql query
        $model_settings = (new Model_Configuration())->model_for($model_name);

        $query = "SELECT count(`". new Key_Column($model_name) ."`) as `total`

            FROM `".$model_settings['table_name']."`  " .

            \Code_Alchemy\Database\SQL\SQLCreator::WHERE(

                \HumanLanguageQuery::create( $query)->conditions );


        if ( $result = (new \Code_Alchemy\Database\Database())->query( $query )) {



            $row = (new Fetch_Associative_Values(new Query_Result($result)))->as_array();

            if (method_exists($result,'close'))

                    $result->close();

            $this->integer_value =

                isset( $row['total'])? $row['total']:
                (int)$row[0]['total'];

        }

    }

}