<?php


namespace Code_Alchemy\Themes\Helpers;

/**
 * Class Theme_Headers
 * @package Code_Alchemy\Themes\Helpers
 */
class Theme_Headers extends Extractable_Content_Collection{

    public function __construct(){

        // Add a test
        $this->tests[] = '/<header(\s+class="([a-zA-Z0-9_-]+)|>)/';

    }

    /**
     * @param array $hits
     * @param $text_line
     * @param $footer_name
     * @return mixed|void
     */
    protected function success_callback( array $hits, $text_line, &$footer_name ){

        // Add footer
        $footer_name = isset($hits[2]) ? $hits[2] : 'canonical';


    }

    /**
     * @return array of footers found
     */
    public function headers(){

        return $this->collection;

    }

}