<?php


namespace Code_Alchemy\Text_Operators;


class String_Values_Replacer {

    /**
     * @var string output after all replacements
     */
    private $output = '';

    /**
     * @param string $string to replace
     * @param array $replacements to perform
     */
    public function __construct( $string, array $replacements ){

        foreach ( $replacements as $regex => $value )

            $string = preg_replace($regex,$value,$string);

        $this->output = $string;

    }

    /**
     * @return string output of replacements
     */
    public function __toString(){ return $this->output; }

}