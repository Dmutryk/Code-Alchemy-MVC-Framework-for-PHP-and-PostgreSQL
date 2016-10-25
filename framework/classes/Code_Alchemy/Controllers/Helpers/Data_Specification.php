<?php


namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Creators\Server_Model_Creator;
use Code_Alchemy\Models\Helpers\Model_Class_For;

class Data_Specification {

    /**
     * @var string Model of data
     */
    public $model = '';

    /**
     * @var string Type of Model
     */
    public $type = '';
    /**
     * @var string Query
     */
    public $query = '';

    /**
     * @var string search to apply for query
     */
    public $search = '';

    /**
     * @var string Directory where assets are saved
     */
    public $directory = '';

    /**
     * @var string referenced model for relationships
     */
    public $references = '';

    public function __construct( array $specification ){

        foreach ( $specification as $name => $value )

            $this->$name = $value;

    }

    /**
     * @return bool true if model exists
     */
    public function model_exists(){

        $test = (string) new Model_Class_For( $this->model );

        return !! (strlen($test)>0);

    }

    /**
     * @return string Database table name
     */
    public function table_name(){

        return strtolower($this->model);

    }

    /**
     * Create the model
     */
    public function create_model(){

        $creator = new Server_Model_Creator($this->model,$this->type);

        $creator->set_options(array(
            'references'=>$this->references
        ));

        $creator->create(false);

    }

    /**
     * Create a needed directory
     */
    public function create_directory(){

        if ( $this->directory ){

            global $webapp_location;

            $path = $webapp_location.$this->directory;

            if ( ! file_exists($path) ){

                @mkdir($path,0777);

                @chmod($path,0777);
            }

        }
    }

}