<?php


namespace Code_Alchemy\Models;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Helpers\Fields_Fetcher;
use Code_Alchemy\Models\Helpers\Generic_Model;
use Code_Alchemy\helpers\model_class_for;

class Model_Fields extends Array_Representable_Object {

    public function __construct( $canonical_name ){

            $fetcher = new Fields_Fetcher( new Model($canonical_name) );

            $this->array_values = $fetcher->as_array();

    }
}