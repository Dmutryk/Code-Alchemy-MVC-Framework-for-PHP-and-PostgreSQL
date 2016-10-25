<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 12/28/14
 * Time: 6:25 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Database\Database_Configuration_File;
use Code_Alchemy\Database\Result\Fetch_Associative_Values;
use Code_Alchemy\Database\Result\Fetch_Fields;
use Code_Alchemy\Database\Result\Query_Result;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\Models\Dynamic_Model;
use Code_Alchemy\Models\Factories\Factory_Wrapper;
use Code_Alchemy\Models\Helpers\Model_Class_For;
use Code_Alchemy\Models\Helpers\Model_Class_Verifier;
use Code_Alchemy\Models\Helpers\Reference_Column_For;
use Code_Alchemy\models\model_wrapper;
use Code_Alchemy\parnassus;
use Code_Alchemy\tools\database;

class Fields_Fetcher {

    /**
     * @var array of Fields
     */
    private $fields = array();

    /**
     * @var string Table Name
     */
    private $table_name = '';

    /**
     * @var \xobjects\tools\database
     */
    private $sql;

    /**
     * @var null|object representative object for Model
     */
    private $model_object = null;

    /**
     * Fetch fields for a given Model
     * @param \business_object $model to fetch
     */
    public function __construct( Dynamic_Model $model ){

        $table_name = $model->table_name();

        // Sve it for later
        $this->table_name = $table_name;

        $model_class = (string) new Model_Class_For($table_name);

        if ( $model_class) $this->model_object =

            (new Model_Class_Verifier($model_class))->is_dynamic_model()?

                new $model_class($table_name):

                    new $model_class;

        $query = "SELECT * FROM `$table_name` WHERE TRUE LIMIT 1";

        $this->sql = new \Code_Alchemy\Database\Database();

        $result = $this->sql->query($query);

        if ( $result){

            $fields = (new Fetch_Fields(new Query_Result($result,$table_name)))->as_array();


            // Process Fields
            $this->process_fields( $fields );

        }
    }

    /**
     * @return bool true if is sortable
     */
    public function is_sortable(){

        return true;
    }

    /**
     * @param array $fields to process
     */
    private function process_fields( array $fields ){

        foreach( $fields as $field ){

            // Get additional information for the field
            $field->additional_information = $this->additional_information( $field->name );

            // Add default value
            $field->default_value = $additional_information['Column_Default'];

            $field->flags = $this->parse_flags( $field->flags );

            $field->type = $this->parse_type($field->type);


            if ( $field->name == 'password'){

                $field->input_type = 'password';

            }
            unset( $field->orgname);
            unset( $field->orgtable);
            unset( $field->def);
            unset( $field->db);
            unset( $field->catalog);

            $field->is_foreign_key = $this->is_foreign_key( $field );

            // If the field is a foreign key
            if ( $field->is_foreign_key ){

                // Get the foreign table referenced
                $field->foreign_table = $this->foreign_table( $field );

                // Get the reference column in the foreign table
                $field->reference_column = $this->reference_column( $field->foreign_table );

                // Get the conditions for lookup in the foreign table
                $field->lookup_conditions = $this->lookup_conditions( $field->table, $field->name );


            }

            // Set input type
            $field->input_type = $this->set_input_type( $field );



            // If image
            if ( $field->input_type == 'file')

                // Set type
            $field->type = 'image';


            $field->is_required = $this->is_required_field( $field );

            // for Time Fields
            if ( in_array($field->name,array('start_time','end_time')))

                $field->type = 'time';

            // if Enumerated
            if ( in_array('Enumerated',$field->flags) ){

                $field->type = 'enum';

                $field->input_type = 'select';

                $database = new \Code_Alchemy\Database\Database();

                $field->enum_values = $database->get_enum_values( $field->table, $field->name );
            }

            // By default label is name
            $field->label = $field->name;

            $this->fields[ $field->name ? $field->name: $field->column_name ] = $field;
        }

    }

    /**
     * @param \Stdclass $field
     * @return bool true if field is required
     */
    private function is_required_field( \Stdclass $field ){

        $is_required = !! in_array('Not NULL',$field->flags);

        if ( $this->model_object ) {

            $required = method_exists($this->model_object,'is_field_required')?

                $this->model_object->is_field_required($field->name):

                $this->model_object->source()->required($field->name);

            $is_required |= $required;
        }


        return $is_required;

    }
    /**
     * Set the INput Type
     * @param \stdClass $field
     * @return string
     */
    private function set_input_type( \stdClass $field ){

        $input_type = 'text';

        // For images and PDF documents
        if ( ! $field->is_foreign_key && preg_match('/(.+_)?image.*/',$field->name) ||
            preg_match('/(.+_)?pdf.*/',$field->name))

            $input_type = 'file';

        return $input_type;

    }

    /**
     * Feetch additional information for a field
     * @param string $field_name to query
     * @return array of additional information
     */
    private function additional_information( $field_name ){

        $information = array();

        if ( $field_name ){

            $schema = (new Database_Configuration_File())->find('database');

            // Construct the Q
            $query = "SELECT Column_Default FROM Information_Schema.Columns WHERE Table_Schema = '$schema' ".
                " AND Table_Name = '$this->table_name' AND Column_Name = '$field_name'";

            $result = $this->sql->query($query);

            if ( $result ){

                $information = (new Fetch_Associative_Values(new Query_Result($result)))->as_array();

            }

            $query2 = "SELECT Column_Comment FROM INFORMATION_SCHEMA.Columns WHERE table_schema='$schema' AND table_name='$this->table_name' and COLUMN_NAME = '$field_name';";

            $result = $this->sql->query($query2);

            if ( $result ){

                $as_array = (new Fetch_Associative_Values(new Query_Result($result)))->as_array();

                $information = array_merge(is_array($information)?$information:[], is_array($as_array)?$as_array:[]);

            }

        }

        return $information;


    }

    /**
     * @param $table
     * @param $field_name
     * @return string Lookup conditions
     */
    private function lookup_conditions( $table, $field_name ){

        $class = (string) new Model_Class_For($table );

        $model = new Factory_Wrapper(

            (new Model_Class_Verifier($class))->is_dynamic_model()?

                (new Dynamic_Model($table))->get_factory():


                    $class::factory()

        );

        return $model->model()->lookup_condition_for( $field_name );


    }

    /**
     * Get a field by name
     * @param $field_name
     * @return \stdClass
     */
    public function get( $field_name ){

        return isset( $this->fields[$field_name])? $this->fields[$field_name]: new \stdClass();

    }

    /**
     * @param $model_name
     * @return string reference column
     */
    private function reference_column( $model_name ){

        return (string) new Reference_Column_For($model_name);

    }

    /**
     * @param \DataSource2 $source
     * @return \DataSource2
     */
    private function get_source( \DataSource2 $source ){ return $source; }

    /**
     * @param \stdClass $field
     * @return string Foreign Table name
     */
    private function foreign_table( \stdClass $field ){

        $table = '';
        if ( in_array('Part of a Key',$field->flags) && preg_match('/([a-z\_]+)_id/',$field->name,$hits))

            $table = $hits[1];

        return $table;

    }

    /**
     * @param \stdClass $field
     * @return bool true if Field is a foreign key
     */
    private function is_foreign_key( \stdClass $field ){

        $result = false;

        if ( in_array('Part of a Key',$field->flags) && preg_match('/([a-z\_]+)_id/',$field->name))

            $result = true;

        return $result;
    }

    /**
     * Parse the Field Type
     * @param $type
     * @return mixed
     */
    private function parse_type( $type ){

        $types = array(
            'tinyint'=>    1,
            'smallint'=>    2,
            'int'=>        3,
            'float'=>        4,
            'double'=>        5,
            'real'=>        5,
            'timestamp'=>    7,
            'bigint'=>        8,
            'mediumint'=>    9,
            'date' =>        10,
            'time' =>        11,
            'datetime' =>    12,
            'year' =>        13,
            'bit' =>        16,
            'decimal' =>    246,
            'text' =>        252,
            'tinytext' =>    252,
            'mediumtext' =>    252,
            'longtext' =>    252,
            'varchar' =>    253,
            'char' =>        254,

        );

        return array_search( $type,$types);
    }

    /**
     * Parse Flags for a Field
     * @param int $flags
     * @return array of resulting flags strings
     */
    private function parse_flags( $flags ){

        $result = array();

        $definitions = array(
            'Not NULL' => 1,
            'Primary Key' => 2,
            'Unique Key' => 4,
            'Is BLOB'=> 16,
            'Unsigned' => 32,
            'Zero Fill' => 64,
            'is Binary' => 128,
            'Enumerated'=> 256,
            'Auto Increment' => 512,
            'Timestamp'=> 1024,
            'Set'=> 2048,
            'Numeric'=> 32768,
            'Part of a Key' => 16384,
            'Group' => 32768,
            'Unique' => 65536
        );

        foreach( $definitions as $label => $bits )

            if ( $flags & $bits )

                $result[] = $label;

        return $result;
    }


    /**
     * @return array of Fields
     */
    public function as_array(){ return $this->fields; }

}
