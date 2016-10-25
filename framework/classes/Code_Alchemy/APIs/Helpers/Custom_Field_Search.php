<?php


namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\REQUEST_URI;

class Custom_Field_Search extends REQUEST_URI {

    /**
     * @var string Field to search
     */
    public $field = '';

    /**
     * @var string value of the field in question
     */
    public $value = '';

    public function __construct( $piece_position = 3 ){

        $piece = urldecode($this->part($piece_position));

        if ( preg_match("/([a-zA-Z0-9_-]+)\:(.+)/",$piece,$hits)){

            $this->field = $hits[1];

            $this->value = $hits[2];
        }


    }

    /**
     * @return bool true if custom search exists
     */
    public function exists(){

        return !! ( $this->field && $this->value );

    }

}