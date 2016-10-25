<?php


namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Extractable_Content_Collection
 * @package Code_Alchemy\Themes\Helpers
 *
 * A collection of extractable content, such as <header>s or <footer>s
 *
 * Provides a convenient way to manage while extracting
 */
abstract class Extractable_Content_Collection extends Alchemist{

    /**
     * @var array of collected content found
     */
    protected $collection = array();

    /**
     * @var array of tests for this type of content
     */
    protected $tests = array();

    /**
     * @param array $hits
     * @param $text_line
     * @param $content_name
     * @return mixed
     */
    abstract protected function success_callback( array $hits, $text_line, &$content_name);

    /**
     * @param $text_line
     * @param $content_name
     * @return bool
     */
    public function check_for_one( $text_line, &$content_name ){

        $is_content = false;

        foreach ( $this->tests as $test )

            if ( preg_match($test,$text_line,$hits)){

                $is_content = true;

                if ( method_exists($this,'success_callback'))

                    $this->success_callback($hits,$text_line,$content_name);

                $this->collection[$content_name] = '';



                break;
            }

        return $is_content;

    }

    /**
     * @param $text_line
     * @param $content_name
     */
    public function add_line( $text_line, $content_name ){

        $this->collection[ $content_name] .= $text_line;

    }





}