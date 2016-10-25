<?php

namespace Code_Alchemy\Core;

/**
 * Class REQUEST
 * @package Code_Alchemy\Core
 */
class REQUEST extends Array_Representable_Object {

    public function __construct(){

        $this->array_values = $_REQUEST;

    }


}

?>