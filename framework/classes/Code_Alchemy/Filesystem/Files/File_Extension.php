<?php

namespace Code_Alchemy\Filesystem\Files;

use Code_Alchemy\Core\Stringable_Object;

class File_Extension extends Stringable_Object {

    public function __construct($filename){

        $this->string_representation = 'unknown';

        if ( preg_match( '/(.+)\.([a-z|A-Z|0-9|_]+)/',$filename,$hits))

            $this->string_representation = $hits[2];

    }

}
