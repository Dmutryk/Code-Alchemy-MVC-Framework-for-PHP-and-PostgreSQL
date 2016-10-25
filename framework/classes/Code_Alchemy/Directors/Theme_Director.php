<?php


namespace Code_Alchemy\Directors;


use Code_Alchemy\Applications\Toolboxes\Helpers\Text_Colorizer;
use Code_Alchemy\Filesystem\Directory_Creator;
use Code_Alchemy\Filesystem\Text_File_As_Lines;
use Code_Alchemy\Themes\Cleanup_After_Themeization;
use Code_Alchemy\Themes\Helpers\Fragment_Inserter;
use Code_Alchemy\Themes\Helpers\HEAD_Section_Normalizer;
use Code_Alchemy\Themes\Helpers\Theme_Component_Directory;
use Code_Alchemy\Themes\Helpers\Theme_Root;
use Code_Alchemy\Themes\HTML5_Theme;
use Code_Alchemy\Themes\HTML_Head_Extractor;
use Code_Alchemy\Themes\HTML_View;
use Code_Alchemy\Themes\Sections_Slicer_and_Dicer;
use Code_Alchemy\Themes\Theme_Manager;
use Code_Alchemy\apis\directory_api;

class Theme_Director {

    /**
     * @var bool by default we don't extract sections
     */
    private $extract_sections = false;

    /**
     * @var string Theme Name
     */
    private $theme_name = '';

    /**
     * @var bool true if may overwrite existing files
     */
    private $is_overwrite = false;

    /**
     * @var string root directory
     */
    private $root = '';

    /**
     * @var HTML5_Theme
     */
    private $theme;

    /**
     * @param string $theme_name
     * @param array $options to apply when themeizing
     */
    public function __construct( $theme_name, $options = array() ){

        $this->theme_name = $theme_name;

        $this->theme = new HTML5_Theme($theme_name);

        // Set optiopns
        foreach ( $options as $name=>$value)

            if ( property_exists($this,$name)) $this->$name = $value;


    }

    /**
     * Analyze and Bind Theme to Code_Alchemy
     * @param bool $verbose true to send output
     */
    public function analyze_and_bind( $verbose = false ){

        if ( $verbose ) echo "\tTheme Director: Ready to rock and roll\r\n\r\n";

        $working_dir = getcwd();

        $theme_root = (string) new Theme_Root($working_dir,$this->theme_name);

        $theme_root_indicated = (string) new Text_Colorizer($theme_root,'light_cyan');

        if ( $verbose ) echo "\tTheme root is $theme_root_indicated\r\n";

        // Create Components Directory
        $dir = new Directory_Creator(getcwd()."/app/views/components/$this->theme_name",0755);

        $dir->create($verbose);



        // Set Home Layout
        $home_layout = "$theme_root/home.php";

        $mgr = $this->theme_manager();

        // Add missing Home Layout
        if ( ! file_exists( $home_layout ) ){

            // Add layout
            $mgr->add_layout('home','index');

        }

        // For each theme template make sure it has a layout
        foreach ( $this->templates() as $template )

            if ( ! $this->has_layout( $template ))

                $mgr->add_layout($template,$template);


        // Foreach Layout

        $layouts_analyzed = 0;

        // Analyze each layout
        foreach ( $this->layouts() as $layout )

        {
            $mgr->analyze_layout( $layout, $verbose );

            $layouts_analyzed++;
        }

        if ( $verbose ) echo "\tAnalyzed $layouts_analyzed layouts\r\n";


        // Foreach component
        foreach ( $this->components() as $component )

            $mgr->analyze_component( $component, $verbose );


        // Analyze and fix JavaScript
        $mgr->analyze_javascript( $verbose );

        // If theme has modules
        if ( $this->theme->has_modules() ){

            if ( $verbose ) echo "$this->theme_name: This theme has modules\r\n";

            foreach( $this->modules() as $module ){

               // if ( $verbose ) echo "$module: Analyzing Module\r\n";

                $module = new HTML_View((string)$module);

                $module->set_replacements( array(

                    "/src=\"'modules\//" => 'src="\''.$this->theme->root_directory().'modules/'

                ));

                $module->perform_replacements();


            }
        }

        // if we should extract sections
        if ( $this->extract_sections ){

            // New! Slice sections
            foreach( $this->layouts() as $layout )

                (new Sections_Slicer_and_Dicer($layout,array(

                    'verbose'=>false,

                    'theme_name' => $this->theme_name

                )))->slice_and_dice();

        }

        // New! Extract head files
        foreach ( $this->layouts() as $layout )

            (new HTML_Head_Extractor($layout))

                ->extract_head();

        // Extract common head components
        new HEAD_Section_Normalizer();

        // Clean up
        new Cleanup_After_Themeization();



    }

    /**
     * @return array of modules for this theme
     */
    private function modules(){

        $modules = array();

        $dir = new directory_api( (string) new Theme_Root(getcwd(),$this->theme_name)."modules");

        foreach ( $dir->directory_listing(true) as $dl){

            if ( is_dir($dl)){

                $views_dir = $dl."/views";

                $layouts_dir = $dl."/views/layouts";

                $views = new directory_api($views_dir);

                foreach ( $views->directory_listing(true) as $view ){

                    if ( (string) new \file_extension_for($view) == 'html')

                        $modules[] = $view;
                }

                $layouts = new directory_api($layouts_dir);

                foreach ( $layouts->directory_listing(true) as $layout){

                    if ( (string) new \file_extension_for($layout) == 'html')

                        $modules[] = new HTML_View($layout);
                }


            }


        }

        return $modules;
    }

    /**
     * @param $template
     * @return bool
     */
    private function has_layout( $template ){

        return !! file_exists(getcwd()."/themes/$this->theme_name/".$template.".php") ;


    }

    /**
     * @param bool $full_path
     * @return array of layouts
     */
    private function layouts( $full_path = false ){

        $layouts = array();

        $exclusions = array(
            'get-tweets1.1.php'
        );

        $dir = new directory_api((string) new Theme_Root(getcwd(),$this->theme_name));

        foreach( $dir->directory_listing($full_path) as $item)

            if ( preg_match('/\.php$/',$item) && ! in_array($item,$exclusions) )

                $layouts[] = $item;

        return $layouts;
    }


    /**
     * @param bool $include_directory
     * @return array components
     */
    private function components($include_directory = false){

        $components = array();

        $dir = new directory_api((string) new Theme_Component_Directory($this->theme_name));

        foreach( $dir->directory_listing($include_directory) as $item)

            if ( preg_match('/\.php$/',$item)  )

                $components[] = $item;

        return $components;
    }

    /**
     * @return array of templates
     */
    private function templates(){

        $layouts = array();

        $dir = new directory_api(getcwd()."/themes/$this->theme_name");

        foreach( $dir->directory_listing() as $item)

            if ( preg_match('/\.html$/',$item) )

                $layouts[] = preg_replace('/\.html$/','',$item);

        return $layouts;
    }

    /**
     * Insert fragments into all files
     */
    public function insert_fragments(){

        // For all layouts
        foreach ( array_merge($this->layouts(true),$this->components(true)) as $path ){

            $file = new Text_File_As_Lines($path);

            $fragments = $file->find_matching_lines("/Code-Alchemy-Fragment: ([a-zA-Z-_0-9]+)/");

            if ( count( $fragments)){

                echo "\tFound a reference to a fragment in $path\r\n";

                // Get inserter
                $inserter = new Fragment_Inserter();

                // if found
                if ( $inserter->fetch_from_comment($fragments[0])){

                    echo "\tFragment name is ".$inserter->fragment_name()."\r\n";

                    // Replace it in file
                    $file->replace_matching_line_with("/Code-Alchemy-Fragment: ".$inserter->fragment_name()."/",$inserter->fragment());

                    //echo $file->join_lines_using("\r\n");

                    $file->commit_changes();

                }
            }


            //$file->commit_changes();

        }


    }

    /**
     * @return Theme_Manager
     */
    private function theme_manager(){

        return new Theme_Manager( $this->theme_name,
        array('root'=>$this->root));

    }

    /**
     * @param array $layout_full_paths
     * @return int number themeized
     */
    public function themeize_layouts( array $layout_full_paths ){

        $new_layouts = array();

        $count_themeized = 0;

        // For each theme template make sure it has a layout
        foreach ( $this->templates() as $template )

            if ( ! $this->has_layout( $template )){

                $this->theme_manager()->add_layout($template,$template);

                // add to new layouts
                foreach( $layout_full_paths as $path ){

                    if ( preg_match("/$template/",$path) )

                        $new_layouts[] =

                            preg_replace('/\.html/','.php',$path);
                }

            }




        foreach ( $new_layouts as $layout )

            if ( $this->theme_manager()->analyze_layout($layout,false,true) )

                $count_themeized++;

        return $count_themeized;

    }
}