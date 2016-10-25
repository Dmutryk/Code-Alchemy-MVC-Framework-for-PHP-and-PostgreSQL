<?php


namespace Code_Alchemy\Builders;


class Service_Builder extends Component_Builder {

    /**
     * @param string $name of service
     * @param string $type of service
     */
    public function __construct( $name, $type = 'base' ){

        $this->add_server_models(array(
            $name=>$type
        ));

    }

}