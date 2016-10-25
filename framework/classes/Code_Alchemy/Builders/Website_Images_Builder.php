<?php


namespace Code_Alchemy\Builders;


class Website_Images_Builder extends Component_Builder {

    public function __construct(){

        $this->add_database_tables(array(

            'website_image'=>'image',

            // New! Add placed Images
            'placed_image' => 'placed_image'

        ));

        $this->add_directories(array(

           getcwd(). '/images/website_image/'=>0777

        ));

    }

}