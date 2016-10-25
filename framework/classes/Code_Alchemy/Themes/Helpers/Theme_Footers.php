<?php


namespace Code_Alchemy\Themes\Helpers;


/**
 * Class Theme_Footers
 * @package Code_Alchemy\Themes\Helpers
 *
 * Collates all the different footers from an HTML5 Theme
 *
 */
class Theme_Footers extends Extractable_Content_Collection{

    public function __construct(){

        // Add a test
        $this->tests[] = '/<footer(\s+class="([a-zA-Z0-9_-]+)|>)/';

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
    public function footers(){

        return $this->collection;

    }

}