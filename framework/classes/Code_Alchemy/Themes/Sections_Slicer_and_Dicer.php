<?php


namespace Code_Alchemy\Themes;
use Code_Alchemy\Core\Random_Password;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Text_Operators\String_Values_Replacer;
use Code_Alchemy\Themes\Helpers\Section_Name;
use Code_Alchemy\Themes\Helpers\Theme_Root;

/**
 * Class Sections_Slicer_and_Dicer
 * @package Code_Alchemy\Themes
 *
 * Slices and dices <section>s within the Layout, so they
 * can be managed within the content management system
 */
class Sections_Slicer_and_Dicer {

    /**
     * @var bool true if this is the first file processed
     */
    private static $is_first_file = true;

    /**
     * @var bool true to be verbose
     */
    private $verbose = false;

    /**
     * @var string Layout name
     */
    private $layout_name = '';

    /**
     * @var string Theme Name
     */
    private $theme_name = '';

    /**
     * @var array of sections found
     */
    private $sections = array();

    /**
     * @param $layout_canonical_name
     * @param array $options
     */
    public function __construct( $layout_canonical_name,array $options = array() ){

        $this->layout_name = $layout_canonical_name;

        foreach( $options as $name => $value )

            if ( property_exists($this,$name)) $this->$name = $value;

        //if ( $this->verbose ) echo "\t".get_called_class().": Ready to slice and dice $layout_canonical_name\r\n";

    }

    /**
     * Slice and Dice the layout into sections
     */
    public function slice_and_dice(){

        // Are we inside a section?
        $in_section = false;

        // The current Section name
        $section_name = '';

        // Output, as Layout file modified
        $output = '';

        // The canonical name of the Layout
        $layout_canonical_name = (string) new \file_basename_for($this->layout_name);

        // The full path filename for the layout
        $filename = (string)new Theme_Root(getcwd(), $this->theme_name) . "/$this->layout_name";

        // Get model for saving sections in DB
        $page_model = (new Model('website_page'))

            ->find("name='$layout_canonical_name'");

        // Have we substituted the sections for the directive yet?
        $is_replaced = false;

        // if we can open the file for reading...
        if ( ( $handle = fopen($filename,'r'))){

            while ( ($line = fgets($handle))!== false ){

                // Report for first file
                if ( self::$is_first_file && $this->verbose ) echo "\tLine: $line";

                if ( preg_match('/<section/',$line,$hits)){

                    if ( $this->verbose ) echo "\tFound an HTML <section> on line $line in Layout $layout_canonical_name\r\n";


                    $section_name = (string) new Section_Name($line,$layout_canonical_name);

                    // Initialize content
                    $this->sections[$section_name] = '';

                    $in_section = true;

                    if ( ! $is_replaced){

                        $output .= file_get_contents(new Code_Alchemy_Root_Path()."/templates/fragments/foreach-model-template.php")."\r\n";

                        $is_replaced = true;
                    }
                }

                if ( $in_section )

                    $this->sections[$section_name] .= $line;

                else

                    $output .= $line;

                if ( preg_match('<\/section>',$line) )

                    $in_section = false;

            }

            // No longer the first file
            self::$is_first_file = false;


            if ( $output )

                file_put_contents($filename,$output);


            fclose($handle);

            // Write sections to database
            foreach ( $this->sections as $name => $section_data ) {

                // If DB Model for Page was found...
                if ( $page_model->exists){

                    $section_model = (new Model('page_section'));

                    if ( ! $section_model

                        ->create_from(array(

                            'website_page_id' => $page_model->id,

                            'name' => $name,

                            'handlebars_template' => (string)

                                new String_Values_Replacer($section_data,array(

                                    '/<\?\=\$theme_root\?\>/' => '{{theme_root}}'
                                ))

                        )))

                        echo "\t".$section_model->error();

                    else

                    {

                        if ( $this->verbose ) echo "\t$name: Section successfully extracted and added to database\r\n";
                    }



                }


            }


        }

        if ( $this->verbose ) echo "\t". get_called_class()." Sliced out ".count( $this->sections)." from Layout $layout_canonical_name\r\n";

    }
}