<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 8:49 AM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Postgres\Is_Postgres_Model;
use Code_Alchemy\Models\Helpers\Reference_Column_For;

/**
 * Class Implicit_Name_Column_LIKE_Query
 * @package Code_Alchemy\APIs\Helpers
 *
 * If user passed a _q= query to the REST API, this comprises an
 * implicit query to look for rows based on values matching the
 * "name column" which is also called the "reference column"
 *
 */
class Implicit_Name_Column_LIKE_Query extends Stringable_Object{

    /**
     * @var string Model Name
     */
    private $model_name = '';

    /**
     * @var bool true if postgres model
     */
    private $is_postgres_model = false;


    public function __construct(){

        $this->model_name = (new REQUEST_URI())->part(2);

        $this->is_postgres_model = (new Is_Postgres_Model($this->model_name))

            ->bool_value();

        $this->string_representation =

            (isset($_REQUEST['_q']) && $_REQUEST['_q'])

            ?

                $this->reference_column()

                . " LIKE %". $this->term(). "%":'';

        //if ( $this->is_development() ) \FB::info(get_called_class().": Implicit query is $this->string_representation");


    }

    /**
     * @return string term
     */
    private function term(){

        return (new Database())->real_escape_string($_REQUEST['_q'], $this->model_name);
    }

    /**
     * @return string Reference column
     */
    private function reference_column(){

        $reference_column = (string)(new Reference_Column_For($this->model_name));

        return $reference_column;

    }
}