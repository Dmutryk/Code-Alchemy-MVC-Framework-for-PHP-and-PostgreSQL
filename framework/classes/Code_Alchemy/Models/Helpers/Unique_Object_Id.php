<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/11/15
 * Time: 5:08 PM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Result\Fetch_Associative_Values;
use Code_Alchemy\Database\Result\Query_Result;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;

/**
 * Class Unique_Object_Id
 * @package Code_Alchemy\Models\Helpers
 *
 * Represents an application-wide unique object id for any Model
 */
class Unique_Object_Id extends Stringable_Object{

    /**
     * @var bool
     */
    private $is_valid;

    /**
     * @param string|null $unique_object_id
     */
    public function __construct( $unique_object_id = null ){

        // Get or set Id
        $id = $unique_object_id ? $unique_object_id : (string) new Random_Password(20,Random_Password::unique_object_id);

        $this->is_valid = (bool) preg_match('/^([A-Za-z]){20}$/',$id);

        /* As long as new one exists
        while ( ! $unique_object_id && $this->exists( $id ) )

            $id = (string) new Random_Password(20,Random_Password::unique_object_id);

        */
        $this->string_representation = $id;

    }

    /**
     * @param $id
     * @return bool true if id already exists
     */
    public function exists( $id ){

        $exists = false;

        if ( $dbResult = (new Database())->query("SELECT `table_row_id` FROM `unique_object_id` WHERE `unique_object_id` = '$id'") ){

            $row = (new Fetch_Associative_Values(new Query_Result($dbResult)))->as_array();

            $exists = isset( $row['table_row_id']) && $row['table_row_id'];

        }

        return $exists;
    }

    /**
     * @return bool true if valid Id
     */
    public function is_valid(){ return $this->is_valid; }

    /**
     * @return Model|null
     */
    public function model(){

        $query = "unique_object_id='$this->string_representation'";

        $intermediate_model = (new Model('unique_object_id'))

            ->find($query);

        return $intermediate_model->table_name ?

            (new Model($intermediate_model->table_name))

            ->find(new Key_Column($intermediate_model->table_name)."='$intermediate_model->table_row_id'")

            : null;


    }

}