<?php


namespace Code_Alchemy\Themes;


use Code_Alchemy\Core\Stringable_Object;

class HTML_View extends Stringable_Object{

    /**
     * @var string full path to view
     */
    private $full_path = '';

    /**
     * @var array of replacements
     */
    private $replacements = array();

    /**
     * @param $full_path
     */
    public function __construct( $full_path ){

        $this->string_representation = $this->full_path = $full_path;

    }

    /**
     * @param array $replacements to set
     */
    public function set_replacements( array $replacements ){

        $this->replacements = $replacements;

    }

    /**
     * Perform replacements
     */
    public function perform_replacements(){

        $contents = file_get_contents($this->full_path);

        foreach ( $this->replacements as $regex=>$repl)

            $contents = preg_replace($regex,$repl,$contents);

        file_put_contents($this->full_path,$contents);

    }

}