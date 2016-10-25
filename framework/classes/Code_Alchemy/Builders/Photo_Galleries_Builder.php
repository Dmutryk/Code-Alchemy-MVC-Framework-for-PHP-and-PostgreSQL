<?php


namespace Code_Alchemy\Builders;


class Photo_Galleries_Builder extends Component_Builder {

    public function __construct(){

        //$this->set_options();

        // Set some options
        $this->set_options(array(
           'references'=>'photo_category',
            'referenced_by'=>'photo'
        ),get_called_class());

        // Add some tables
        $this->add_database_tables(array(
            'photo_category'=>'sortable',
            'photo'=> 'photo_with_reference'
        ));

        // Add some server models
        $this->add_server_models(array(
            'photo_category'=>'sortable_with_referenced_by',
            'photo'=>'photo_with_reference'
        ));

        // And some directories
        $this->add_directories(array(
            getcwd().'/images/photo/'=>0777
        ));
    }
}