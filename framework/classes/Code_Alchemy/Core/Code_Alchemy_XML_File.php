<?php


namespace Code_Alchemy\Core;


class Code_Alchemy_XML_File extends Stringable_Object{

    public function __construct(){

        global $webapp_location;

        $this->string_representation = $webapp_location."/app/xml/x-objects.xml";

    }
}