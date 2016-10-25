<?php


namespace Code_Alchemy\Models;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\JSON\JSON_File;

class Model_Configuration extends JSON_File{

    /**
     * Create a new instance
     */
    public function __construct(){

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();

        parent::__construct( array(

            // Set path
            'file_path' => $webapp_location.'/app/config/models.json',

            // Set template
            'template_file' => '/templates/JSON/models.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            // Automatically load file
            'auto_load'=>true

        ));

    }

    /**
     * @param string $model_key
     * @return array Model data
     */
    public function model_for( $model_key ){

        $model = array();

        if ( isset( $this->data['models'][$model_key]) )

            $model = $this->data['models'][$model_key];


        else {



           if ( $this->is_development() ) \FB::warn(get_called_class().":$model_key: No such model set in models.json");


            /* If we should create it
            if ( (new Configuration_File())->auto_create_models() ){

                \FB::info("$model_key: Dynamic Model will be added");

                // Get the table
                $table = new Database_Table( $model_key );

                // If Table exists and Model was added
                if ( $table->exists &&

                    $this->add_model_from_table( $table )
                )

                {

                    $model = $this->data['models'][$model_key];

                }

            }
            */

        }

        return $model;

    }

    /**
     * Adds a new Model Configuration based on an existing database table
     * @param Database_Table $table
     * @return bool true if added successfully
     */
    public function add_model_from_table( Database_Table $table ){

        $models = $this->find('models');

        $models[ $table->name() ] = array(

            'table_name'=>$table->name(),

            'key_column'=>$table->key_column(),

            'safe_delete'=> $table->supports_safe_delete(),

            'reference_column' =>$table->guess_reference_column(),

            'auto_parse_relationships' => false,

            'is_updateable' => true



        );

        $this->set('models',$models);

        return $this->update();

    }

    /**
     * @param $model_name
     * @param array $columns
     * @return bool
     */
    public function set_model_columns( $model_name, array $columns ){

        // Reload file
        $this->reload();

        // First get all Models
        $models = $this->find('models');

        if ( isset( $models[$model_name])){

            //\FB::info(get_called_class()." Setting columns for model $model_name");

            $models[$model_name]['columns'] = $columns;

        }


        return $this->set('models',$models)->update();
    }

    /**
     * Add a table or view to list
     * @param string $table_or_view_name
     */
    public function add_table_or_view( $table_or_view_name ){

        $this->reload();

        $tables_and_views = $this->find('tables_and_views');

        if ( ! in_array($table_or_view_name,is_array($tables_and_views)?$tables_and_views:[])){

            $tables_and_views[] = $table_or_view_name;

            $this->set('tables_and_views',$tables_and_views)

                ->update();
        }

    }

}