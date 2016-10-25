<?php


namespace Code_Alchemy\JSON;


class Displayed_JSON_Output {

    /**
     * @param (array|object) $data to display as JSON
     */
    public function __construct( $data ){

        $data = is_object( $data ) ? $data->as_array(): $data;

        header('Content-Type: application/json');

        echo json_encode($data);

    }

}