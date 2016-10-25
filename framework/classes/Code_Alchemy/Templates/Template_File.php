<?php


namespace Code_Alchemy\Templates;


class Template_File {

    /**
     * @var string representation of template
     */
    protected $string = '';

    /**
     * @return string representation of template
     */
    public function __toString(){

        return $this->string;
    }

}