<?php


namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class Theme_Root extends Stringable_Object {

    /**
     * @var array of theme mappings for specialized roots
     */
    private $theme_mapping = array(
        'miveus'=>'/src/',

        'rdash-angular'=>'/dist/',

        'rdash'=>'/src/'
    );

    /**
     * @param string $working_dir for the theme
     * @param string $theme_name for the theme
     */
    public function __construct( $working_dir, $theme_name ){

        // Default
        $this->string_representation = "$working_dir/themes/$theme_name/";

        if ( isset( $this->theme_mapping[ $theme_name ]))

            $this->string_representation .= $this->theme_mapping[$theme_name];

    }

}