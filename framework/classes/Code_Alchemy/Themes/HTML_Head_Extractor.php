<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/8/15
 * Time: 12:39 PM
 */

namespace Code_Alchemy\Themes;


use Code_Alchemy\Cache\Cache_Key;
use Code_Alchemy\Cache\Fast_Cache;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Templates\HTML5\Layout_Pre_Stub;
use Code_Alchemy\Templates\Layouts\Layout_Template;
use Code_Alchemy\Text_Operators\Replace_Text_Chunk_With_String;
use Code_Alchemy\Themes\Helpers\Extract_HTML_HEAD;
use Code_Alchemy\Themes\Helpers\Theme_Component_Directory;
use Code_Alchemy\Themes\Layouts\Layout_Path;

class HTML_Head_Extractor {

    /**
     * @var Fast_Cache to accumulate different variations of <HEAD>
     */
    private static $cache;

    /**
     * @var string Layout Path
     */
    private $layout_path = '';

    /**
     * @var string name of layout
     */
    private $layout_name = '';

    /**
     * @var bool
     */
    private $verbose = false;

    /**
     * @param string $layout_name
     * @param array $options
     */
    public function __construct( $layout_name, array $options = array()){

        $this->layout_name = $layout_name;

        // Initialize Cache as needed
        if ( ! self::$cache ) self::$cache = new Fast_Cache(100);

        // Get Path to layout
        $this->layout_path = (string)new Layout_Path( $layout_name );

        if ( $this->verbose ) echo "\tLayout path $this->layout_path\r\n";

    }

    /**
     * Extract the head from the file
     */
    public function extract_head(){

        // Get Layout text
        $layout_text = (string)new Layout_Template($this->layout_path);

        if ( ! $layout_text ) echo "\tWARNING: No Layout text found for $this->layout_path\r\n";

        // Replace it
        file_put_contents(

        // Layout file
            $this->layout_path,

            (string) new Replace_Text_Chunk_With_String($layout_text,'/<head>/','/<\/head>/',
                // Replace with require statement
                '<?php require_once $webroot . "/app/views/components/'

                . new Theme_Name_Guess(). "/". $this->head_name_for( (string) new Extract_HTML_HEAD($layout_text) )

                .".php\"; ?>"

            )

        );

    }

    /**
     * @param string $head_text
     * @return string Head Name
     */
    private function head_name_for( $head_text ){

        // Build cache key
        $cache_key = "head-". (string)new Cache_Key($head_text);

        if ( $this->verbose ) echo "\tCache key is $cache_key\r\n";

        // Not in cache?
        if ( ! self::$cache->get($cache_key)){

            // Add it
            self::$cache->set($cache_key,$head_text);

            // Externalize to file
            $head_component_path = new Theme_Component_Directory((string)new Theme_Name_Guess())
                . "/head-$this->layout_name.php";
            file_put_contents(

                // Get path the new component
                $head_component_path
                ,

                // Add prestub
                new Layout_Pre_Stub().$head_text

            );

            shell_exec("git add $head_component_path");

        }

        // return layout name as name
        return "head-$this->layout_name";
    }

}