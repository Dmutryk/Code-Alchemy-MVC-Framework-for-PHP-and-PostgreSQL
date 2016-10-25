<?php


namespace Code_Alchemy\Themes;


use Code_Alchemy\Themes\Helpers\Theme_Root;

class HTML5_Theme {

    /**
     * @var array of Main Menu tags per Theme
     */
    private $main_menu_tags = array(

        // Use this for Neon Theme to recurse and fine Menu
        'neon'=> array(
            'start'=>'<ul id="main-menu" class="main-menu">',
            'nest'=>'\<ul(\>|\s)',
            'end'=>'<\/ul>'
        )
    );

    /**
     * @var string theme name
     */
    private $theme_name = '';

    /**
     * @param $theme_name
     */
    public function __construct( $theme_name){

        $this->theme_name = $theme_name;

    }

    /**
     * @return bool true if theme has modules
     */
    public function has_modules(){

        return !! in_array($this->theme_name,array('miveus'));

    }

    /**
     * @param bool $show_full
     * @return string theme root
     */
    public function root_directory( $show_full = false ){

        return (string) new Theme_Root($show_full?getcwd():'',$this->theme_name);

    }

    /**
     * Obtain Main Menu Tag, if any
     * @param $layout_contents
     * @param $specification
     * @return bool
     */
    public function main_menu_tag( $layout_contents, &$specification ){

        $result = false;

        foreach( (new Theme_Manager_Configuration())

                     ->find('main-menu-tags') as $label => $tag_group )

            if ( preg_match("/".$tag_group['start']."/",$layout_contents) ){

                $specification = $tag_group;

                $result = true;

                break;
            }

        return $result;

    }

}