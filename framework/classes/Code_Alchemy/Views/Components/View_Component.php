<?php


namespace Code_Alchemy\Views\Components;


use Code_Alchemy\helpers\Smart_File_Copier;
use Code_Alchemy\Templates\HTML5\Layout_Pre_Stub;
use Code_Alchemy\Themes\Helpers\Theme_Component_Directory;

class View_Component {

    /**
     * @var string Name of component
     */
    private $name = '';

    /**
     * @var string Content for component
     */
    private $content = '';

    /**
     * @var string Theme name
     */
    private $theme_name = '';

    /**
     * @var bool
     */
    private $verbose = false;

    /**
     * @param $name
     * @param $content
     * @param string $theme_name
     * @param array $options
     */
    public function __construct( $name, $content, $theme_name = '', array $options = array() ){

        $this->name = $name;

        $this->content = $content;

        $this->theme_name = $theme_name;

        foreach ( $options as $name2 => $value)

            if ( property_exists($this,$name2))

                $this->$name2 = $value;

        if ( $this->verbose) echo get_called_class().": name = $name\r\n";

    }

    /**
     * @return bool true if this component file already exists
     */
    public function file_exists(){


        return !! file_exists($this->file_path());

    }

    /**
     * @return string File Path
     */
    private function file_path(){

        $file_path = (string)new Theme_Component_Directory($this->theme_name) . "$this->name.php";

        //echo "File path is $file_path\r\n";

        return $file_path;


    }

    /**
     * @return int number of bytes written
     */
    public function write_to_file(){

        $filename = $this->file_path();

        //echo get_called_class().": Writing to file $filename\r\n";

        // Get a smart file copier

        // Write content to file
        $result = file_put_contents(

            $filename,(string) new Layout_Pre_Stub(). $this->content);

        // Add to Git
        shell_exec("git add $filename >/dev/null 2>/dev/null");

        return $result;

    }

}