<?php


namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;

class Fragment_Inserter {

    /**
     * @var string Fragment
     */
    private $fragment = '';

    /**
     * @var string Name of fragment
     */
    private $name = '';

    /**
     * @param $comment
     * @return bool
     */
    public function fetch_from_comment( $comment ){

        $result = false;

        if ( preg_match("/Code-Alchemy-Fragment: ([a-zA-Z-_0-9]+)/",$comment,$hits)){

            $this->name = $hits[1];

            $fragment_path = (string) new Code_Alchemy_Root_Path()."/templates/fragments/".$hits[1].".php";

            if ( file_exists($fragment_path)){

                $result = true;

                $this->fragment = file_get_contents($fragment_path);

            }

        }

        return $result;
    }

    /**
     * @return string fragment name
     */
    public function fragment_name(){

        return $this->name;

    }

    public function fragment(){

        return $this->fragment;

    }

}