<?php


namespace Code_Alchemy\Vendors\Base;


use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\JSON\JSON_File;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\JSON\Displayed_JSON_Output;
use Code_Alchemy\Core\Array_Representable_Object;

class Base_Client  extends Array_Representable_Object {

    /**
     * @var string Manifest directory
     */


    /**
     * Create a new instance
     */
    public function __construct(  ){

        $this->configuration = (array)(new Configuration_File())->find('base-service');
        $this->token = @$this->configuration['token'];
    }

}