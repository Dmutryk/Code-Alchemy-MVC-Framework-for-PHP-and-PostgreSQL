<?php


namespace Code_Alchemy\Themes\Helpers;


/**
 * Class Theme_Footers
 * @package Code_Alchemy\Themes\Helpers
 *
 * Collates all the different Nav sections from an HTML5 Theme
 *
 */
class Theme_Nav_Sections extends Extractable_Content_Collection{

    public function __construct(){

        // Add tests
        $this->tests[] = '/<nav(\s+id="navigation"\s+)?(\s+class="([a-zA-Z0-9_-]+)|>)/';

        $this->tests[] = '/<ul id\=\"navigation/';

    }

    /**
     * @param array $hits
     * @param $text_line
     * @param $nav_name
     * @return mixed|void
     */
    protected function success_callback( array $hits, $text_line, &$nav_name ){

        // Add footer
        $nav_name = isset($hits[2]) ? $hits[2] : 'canonical';

    }

    /**
     * @return array of navs found
     */
    public function navs(){

        return $this->collection;

    }

}