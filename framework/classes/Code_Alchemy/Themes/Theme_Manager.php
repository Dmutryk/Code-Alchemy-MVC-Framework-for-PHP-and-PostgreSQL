<?php


namespace Code_Alchemy\Themes;


use Code_Alchemy\Core\Managed_Component;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Themes\Helpers\Theme_Component_Directory;
use Code_Alchemy\Themes\Helpers\Theme_Footers;
use Code_Alchemy\Themes\Helpers\Theme_Headers;
use Code_Alchemy\Themes\Helpers\Theme_Heads;
use Code_Alchemy\Themes\Helpers\Theme_Nav_Sections;
use Code_Alchemy\Themes\Helpers\Theme_Root;
use Code_Alchemy\Themes\Text_Operators\Line_By_Line_Extractor;
use Code_Alchemy\Views\Components\View_Component;

/**
 * Class Theme_Manager
 * @package Code_Alchemy\Themes
 *
 * Theme Manager does most of the hands-on work, or delegation of same,
 * for programming HTML5 Themes and their respective Layouts
 */

class Theme_Manager extends Managed_Component {

    /**
     * @var string theme name
     */
    private $theme_name = '';

    /**
     * @var bool true if overwrite allowed
     */
    private $is_ovewrite = false;


    /**
     * @param string $theme_name to program
     * @param array $options to set while programming
     */
    public function __construct( $theme_name, $options = array() ){

        $this->theme_name = $theme_name;

        // Set optiopns
        foreach ( $options as $name=>$value)

            if ( property_exists($this,$name)) $this->$name = $value;



    }

    /**
     * @param string $component to analyze
     * @param bool $verbose to send output to screen
     */
    public function analyze_component( $component, $verbose = false ){

        //if ( $verbose ) echo "Analyzing Component $component for theme $this->theme_name\r\n";

        $component_file = ((string) new Theme_Component_Directory($this->theme_name)).$component;

        //if ( $verbose ) echo "Component File is $component_file\r\n";

        // Check for pre stub
        if ( ! $this->has_pre_stub( $component_file ) )

            $this->add_pre_stub( $component_file, $verbose );


        // Analyze and replace values
        $this->analyze_and_replace($verbose, $component_file);

    }

    /**
     * Analyze Layout and make Replacements
     * @param bool $verbose to send output to sscreen
     * @param string $file to analyze
     */
    private function analyze_and_replace( $verbose = false, $file ){

        // Collate the footers
        $footers = new Theme_Footers();

        // Collate the headers
        $headers = new Theme_Headers();


        // Collate the Navs
        $navs = new Theme_Nav_Sections();

        $is_changed = false;

        $data = '';

        $layout_handle = fopen($file,'r');

        // Get Layout Contents as a string
        $layout_contents = file_get_contents($file);

        // Load from File
        $replacements = (new Theme_Manager_Configuration())->find('replacements');

        // Are we inside the Nav?
        $inside_nav = false;

        // Are we inside the header?
        $inside_header = false;

        // Inside footer
        $inside_footer = false;

        // Are we inside the head?
        //$inside_head = false;

        // Head text, for externalization
        $head = '';

        // Are we inside specialized content?
        $inside_specialized_content = false;

        $specialized_content_name = '';

        $specialized_content = '';


        // Get Theme
        $theme = new HTML5_Theme((string) new Theme_Name_Guess());

        // Get a line-by-line extractor
        $extractor = new Line_By_Line_Extractor(array(

            'verbose' => false

        ));

        // If Theme has main menu

        $specification = array();

        // Footer name
        $footer_name = '';

        // Header name
        $header_name = '';

        // nav name
        $nav_name = '';


        if ( $theme->main_menu_tag( $layout_contents, $specification ) ){

            echo "\tFound a Main Menu Tag to extract the Navigation\r\n";

            // Specify an extraction
            $extractor->extract( 'nav' , $specification );


        }


        // Go through file
        while ( ($line = fgets($layout_handle)) !== false ){

            // Flag to avoid underruns on extracted content
            $content_just_ended = false;

            // Feed a line to the extractor
            if ( $result = $extractor->consume( $line )){

                // Start of specialized content
                if ( ! preg_match('/components/',$file) && preg_match( "/start\:([a-z_]+)/",$result,$hits)){

                    $inside_specialized_content = true;

                    $specialized_content_name = $hits[1];

                    // Add the reference to externalized content
                    $data .= '<?php require_once $webroot."/app/views/components/'.$this->theme_name. '/'.$hits[1].'.php";?>';

                    $data .= "\r\n";

                    $is_changed = true;

                }


                if ( preg_match( "/end\:([a-z_]+)/",$result,$hits)){

                    $inside_specialized_content = false;

                    $specialized_content = $extractor->extraction($hits[1]);

                    $content_just_ended = true;

                }

            }

                //echo "\r\nExtractor found $result\r\n";


            // If index reference
            if ( preg_match('/href="index.html"/',$line)){

                $line = preg_replace('/href="index.html"/','href="/"',$line);

                $is_changed = true;
            }

            // Did we find a footer?
            if (

                ! preg_match('/components/',$file) &&

                $footers->check_for_one( $line, $footer_name ) ){

                // Indicate we are inside the header
                $inside_footer = true;

                // Add the reference to externalized header
                $data .= '<?php require_once $webroot."/app/views/components/'.$this->theme_name.'/'.$footer_name.'.php";?>';

                // Yes, the file has changed
                $is_changed = true;

            }

            // Or a header?
            if (

                ! preg_match('/components/',$file) &&

                $headers->check_for_one( $line, $header_name) ){

                // Indicate we are inside the header
                $inside_header = true;

                // Add the reference to externalized header
                $data .= '<?php require_once $webroot."/app/views/components/'.$this->theme_name.'/'.$header_name.'.php";?>';

                // Yes, the file has changed
                $is_changed = true;

            }


            /* Did we find the head? but not in a component!
            if ( ! preg_match('/components/',$file) && preg_match( '/<head>/',$line)){

                // Indicate we are inside the header
                $inside_head = true;

                // Add the reference to externalized header
                $data .= '<?php require_once $webroot."/app/views/components/'.$this->theme_name.'/head.php";?>'."\r\n";

                // Yes, the file has changed
                $is_changed = true;

            }*/


            // Perform replacements on line as required
            foreach ( $replacements as $set ){

               // var_dump($set);

                $this->line_replacement( $line, $set['regex'],$set['replacement'],$is_changed);

            }

            // If inside footer
            if ( $inside_footer ) $footers->add_line( $line, $footer_name );

            // If inside header
            if ( $inside_header ) $headers->add_line( $line, $header_name );

            // If inside header add it to data
            //if ( $inside_header ) $header .= $line;

            // If inside head add it to data
            //if ( $inside_head ) $head .= $line;

            // Are we inside nav?
            if ( preg_match('/<nav/',$line) || preg_match('/<ul id\=\"navigation/',$line)){

                $inside_nav = true;

            }

            // Analysis for inside Nav
            if ( $inside_nav || preg_match('/main_menu/',$file)  ){

                if ( preg_match('/href="([a-z-_0-9]+)\.html"/',$line,$hits)){

                    $line = preg_replace('/href="([a-z-_0-9]+)\.html"/','href="'.$hits[1].'"',$line);

                    $is_changed = true;

                }


            }


            // Are we done with nav?
            if ( preg_match('/<\/nav/',$line) || preg_match('/\<\!\-\- \/Navigation \-\-\>/',$line)){

                $inside_nav = false;

            }


            // Add, as long as we aren't inside the header or head
            if (
                ! $content_just_ended &&
                ! $inside_header &&
                //! $inside_head &&
                ! $inside_specialized_content &&
                ! $inside_footer
            )

                $data .=$line;


            // At the end of the header?
            if ( preg_match('/<\/header/',$line)){

                // No longer in header
                $inside_header = false;

            }

            /* At the end of the head?
            if ( preg_match('/<\/head>/',$line)){

                // No longer in header
                $inside_head = false;
            }*/

            // At end of footer
            if ( preg_match('/<\/footer>/',$line)){

                // No longer in header
                $inside_footer = false;
            }




        }

        fclose($layout_handle);

        if ( $is_changed ) file_put_contents($file,$data);

        /* Did we get a header?
        if ( strlen( $header )){

            //if ( $verbose ) echo "Found a header to externalize\r\n";

            // Externalize it
            $this->externalize_content( 'header', $header );

        }
        */

        // Foreach footer found
        foreach ( $footers->footers() as $fname => $fcontent )

            // Externalize it
            $this->externalize_content($fname,$fcontent);


        // Foreach header found
        foreach ( $headers->headers() as $fname => $fcontent )

            // Externalize it
            $this->externalize_content($fname,$fcontent);


        /* Did we get a head?
        if ( strlen( $head ))

            // Headize it
            $this->externalize_content( 'head',$head );

        */
        // Did we find specialized content
        if ( strlen( $specialized_content) && strlen( $specialized_content_name))

            // Externalize it
            $this->externalize_content($specialized_content_name,$specialized_content);

        // Add to Git
        shell_exec("git add $file >/dev/null 2>/dev/null");


    }

    /**
     * Analyze a Layout
     * @param $layout
     * @param bool $has_full_path
     * @param bool $verbose
     */
    public function analyze_layout( $layout, $verbose = false, $has_full_path = false){

        //echo "\tAnalyzing Layout: $layout\r\n";

        $layout_file =

            $has_full_path ?

                $layout :

            (string) new Theme_Root(getcwd(),$this->theme_name)."/$layout";

        // Check for pre stub
        if ( ! $this->has_pre_stub( $layout_file ) )

            $this->add_pre_stub( $layout_file, $verbose );


        // Check for Post Stub
        if ( ! $this->has_post_stub( $layout_file ) )

            $this->add_post_stub( $layout_file, $verbose );

        // Analyze layout and make replacements
        $this->analyze_and_replace($verbose,$layout_file);

        return true;

    }

    /**
     * Externalize some content
     * @param string $type
     * @param string $content
     */
    public function externalize_content( $type, $content ){

        // Create View Component
        $component = new View_Component(
            $type,
            $content,
            $this->theme_name,
            array(

                'verbose' => false
            )
        );

        // Doesn't exist yet?
        if ( ! $component->file_exists() ){

            // Create it!
            if ( $component->write_to_file() ) {}

                //echo "$type Component View successfully created\r\n";

            else

                echo "Error: Unable to write $type View Component\r\n";


        } else {

            //echo "\tView Component $type already exists\r\n";
        }



    }

    /**
     * Perform a line replacement as required
     * @param $line
     * @param $match
     * @param $replace
     * @param $is_changed
     */
    private function line_replacement( &$line, $match, $replace, &$is_changed){

        //echo $match ."\r\n";
        // Javascript with Relative Path?
        if ( preg_match($match,$line,$hits)){

            $line = preg_replace($match,$replace,$line);

            $is_changed = true;
        }

    }

    /**
     * @param string $layout_file to analyze
     * @return bool true if layout has pre stub
     */
    private function has_pre_stub( $layout_file ){

        $text = file_get_contents($layout_file );

        return !! preg_match('/LAYOUT_PRE_STUB/',$text);

    }

    /**
     * @param string $layout_file to analyze
     * @return bool true if layout has post stub
     */
    private function has_post_stub( $layout_file ){

        $text = file_get_contents($layout_file );

        return !! preg_match('/LAYOUT_POST_STUB/',$text);

    }


    /**
     * @param $layout_file
     * @param bool $verbose
     */
    private function add_pre_stub( $layout_file, $verbose = false){

        $replacements = preg_replace(
            '/__theme_root__/', (string)new Theme_Root('', $this->theme_name), file_get_contents($this->root() . "/templates/html5_theme/layout_pre_stub.php"));

        $replacements = preg_replace('/__mynamespace__/',(string)new Namespace_Guess,$replacements);

        //var_dump($replacements);

        file_put_contents($layout_file,
            $replacements ."\r\n".file_get_contents($layout_file)
        );

    }

    /**
     * @param $layout_file
     * @param bool $verbose
     */
    private function add_post_stub( $layout_file, $verbose = false){

        //if ( $verbose ) echo "$layout_file: Adding missing post stub\r\n";

        file_put_contents($layout_file,file_get_contents($layout_file)."\r\n".preg_replace( '/__name__/',(string) new Namespace_Guess(),file_get_contents( $this->root()."/templates/html5_theme/layout_post_stub.php")));

    }



    /**
     * Add a Layout from an existing theme template
     * @param $layout_name
     * @param $template_name
     */
    public function add_layout( $layout_name, $template_name ){

        $theme_root = (string) new Theme_Root(getcwd(),$this->theme_name);

        // Smart Copy File
        $copy = new Smart_File_Copier("$theme_root/$template_name.html",
            "$theme_root/$layout_name.php",array(),$this->is_ovewrite
        );

        if ( ! $copy->copy() ) echo $copy->error."\r\n";

        // Add to database
        (new Model('website_page'))

            ->create_from(array(

                'name' => $layout_name

            ));
    }

    /**
     * Analyze theme javascript
     * @param bool $verbose
     */
    public function analyze_javascript( $verbose = false ){

        //if ( $verbose ) echo "Analyzing JavaScript...\r\n";

        $files = array(

            // Added for miveus
            getcwd()."/themes/$this->theme_name/src/bundles/app.js",
            getcwd()."/themes/$this->theme_name/src/bundles/vendor.js",

            // Added for Allec
            getcwd()."/themes/$this->theme_name/js/custom.js",
            getcwd()."/themes/$this->theme_name/external/twitterfeed/twitterfeed.js",

            // END added for Allec
            getcwd()."/themes/$this->theme_name/js/main.js",
            getcwd()."/themes/$this->theme_name/js/theme-options/theme-options.js",
        );

        $replacements = array(

            // Added for Miveus
            "/'modules\//"=>"'/themes/$this->theme_name/src/modules/",
            '/"bundles\/"/'=> '"'. (string) new Theme_Root('',$this->theme_name).'/bundles/"',
            // Added for Allec
            "/'external\//"=>"'/themes/$this->theme_name/external/",
            "/'get-tweets/"=>"'/themes/$this->theme_name/get-tweets",
            // END added for Allec

            "/'js\//"=>"'/themes/$this->theme_name/js/",
            '/"css\//'=>'"/themes/'.$this->theme_name.'/css/'
        );

        foreach ( $files as $file ){

            if ( file_exists($file)){

                $is_changed = false;

                $fh = fopen($file,'r');

                $data = '';

                while ( ($line = fgets($fh))!== false ){

                    foreach( $replacements as $match=>$replace)

                        $this->line_replacement($line,$match,$replace,$is_changed);

                    $data .=$line;

                }

                if ( $is_changed ) file_put_contents($file,$data);

            }


        }
    }

}