<?php


namespace Code_Alchemy\Applications\Web_Director;


use Code_Alchemy\Models\Helpers\Generic_Model;
use Code_Alchemy\helpers\model_class_for;
use Code_Alchemy\tools\database;

class Configuration {

    /**
     * @var array of settings, saved in db
     */
    private $settings = array();

    public function __construct(){

        // Get database
        $database = new database();

        // If configuration table doesn't exist
        if ( ! $database->has_table('parnassus_configuration'))

            // Create it
            $database->create_table('parnassus_configuration','configuration');

        $model_class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For('parnassus_configuration');

        if ( $model_class ){

            $model = new $model_class("name='admin-console'");

            if ( ! $model->exists )

                $model = $model_class::create_from_associative(array(
                    'name'=>'admin-console',
                    'data'=> serialize( array() )
                ));

            $this->settings = (array) unserialize($model->data);

        }

    }

    /**
     * Update the Configuration settings
     * @param array $settings
     * @return bool
     */
    public function update( array $settings ){

        $model_class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For('parnassus_configuration');

        $model = new Generic_Model(new $model_class("name='admin-console'"));

        return $model->model()->update_from_associative(array(

            'data'=>serialize( $settings )

        ));

    }

    /**
     * @return array of settings
     */
    public function settings(){ return $this->settings; }

}