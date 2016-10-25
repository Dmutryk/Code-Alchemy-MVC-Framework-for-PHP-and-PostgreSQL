<?php


namespace Code_Alchemy\Builders;


class Revolution_Slider_Builder extends Component_Builder {

    public function __construct(){

        $this->add_database_tables(array(
            'slide'=>'revolution_slide'
        ));

        $this->add_server_models(array(
            'slide'=>'revolution_slide'
        ));
    }

}