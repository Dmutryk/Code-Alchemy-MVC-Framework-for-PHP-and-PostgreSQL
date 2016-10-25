<?php


namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class Request_Method extends Stringable_Object {

    /**
     * Request_Method constructor.
     * @param array|null $data
     */
    public function __construct( array $data = null ){

        // Use data or REQUEST
        $data = $data ? $data : $_REQUEST;
        
        $this->string_representation = strtoupper($_SERVER['REQUEST_METHOD'] );

        if ( isset( $data['__DELETE__']))

            $this->string_representation = 'DELETE';

        if ( isset( $data['_PARNASSUS_SIMULATE_PUT']))

            $this->string_representation = 'PUT';

        if ( isset( $data['_PARNASSUS_SIMULATE_DELETE']))

            $this->string_representation = 'DELETE';

        //if ( $this->is_development() )  \FB::info(get_called_class().": server request method is $this->string_representation");

    }
}