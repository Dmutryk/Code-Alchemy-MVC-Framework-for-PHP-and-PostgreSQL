<?php


namespace Code_Alchemy\Filesystem;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Heuristics\Heuristic_Class_Name;
use Code_Alchemy\Text_Operators\String_Values_Replacer;

class Text_File_As_Lines extends Array_Representable_Object{

    /**
     * @var string Full Path to File
     */
    private $file_full_path = '';

    /**
     * @var int Line location for flagged text
     */
    private $flagged_text_location = -1;

    /**
     * @var bool true if text has changed
     */
    private $text_has_changed = false;

    /**
     * @param string $file_full_path
     */
    public function __construct( $file_full_path, $text_to_flag = null ){

        $this->file_full_path = $file_full_path;

        if ( file_exists($file_full_path)){

            $handle = fopen($file_full_path,'r');

            while ( ($line = fgets($handle)) !== false ){

                $this->array_values[] = $line;

                if ( $text_to_flag && preg_match("/$text_to_flag/",$line)){

                    $this->flagged_text_location = count($this->array_values);

                }
            }


            fclose($handle);
        } else {

            echo $file_full_path.": No such file</span>";

        }
    }

    /**
     * @return bool true if file has flagged text
     */
    public function has_flagged_text(){

        return !! ( $this->flagged_text_location>0);

    }


    /**
     * Insert some lines before another one in the file
     * @param $line_match
     * @param array $lines_to_insert
     */
    public function insert_lines_before( $line_match, array $lines_to_insert ){

        $new_values = array();

        foreach ( $this->array_values as $value ){

            if ( preg_match("/$line_match/",$value)){

                $this->text_has_changed = true;

                foreach ( $lines_to_insert as $new_line )

                    $new_values[] = $new_line;

            }

            $new_values[] = $value;

        }

        $this->array_values = $new_values;

    }

    /**
     * Insert some lines before another one in the file
     * @param $line_match
     * @param array $lines_to_insert
     * @return Text_File_As_Lines
     */
    public function insert_lines_after( $line_match, array $lines_to_insert ){

        $new_values = array();

        foreach ( $this->array_values as $value ){

            $new_values[] = $value;

            if ( preg_match("/$line_match/",$value)){

                $this->text_has_changed = true;

                foreach ( $lines_to_insert as $new_line )

                    $new_values[] = $new_line;

            }


        }

        $this->array_values = $new_values;

        return $this;

    }


    /**
     * Commit changes back to file
     */
    public function commit_changes(){

        $result = 0;

        if ( $this->text_has_changed){

            $files = implode("",$this->array_values);

            //echo "File full path = $this->file_full_path\r\n";

            $result = file_put_contents($this->file_full_path,$files);
        }

        return $result;

    }

    /**
     * Run a Heuristic Function on the file's lines
     * @param string $name of heuristic
     * @param array $applicable_lines to use when performing
     * @param array $options to apply or additional parameters
     */
    public function run_heuristic(
        $name,
        array $applicable_lines = array(),
        array $options = array()
    ){

        $class =  (string) new Heuristic_Class_Name((string) new CamelCase_Name($name));

        $operator = new $class($applicable_lines, $this->array_values,$options);

        $this->array_values = $operator->as_array();

        $this->text_has_changed = true;

    }

    /**
     * Replace text in file
     * @param array $replacements to perform
     */
    public function replace_text( array $replacements ){

        $lines = array();

        foreach ( $this->as_array() as $line )

            $lines[] = (string) new String_Values_Replacer($line,$replacements);

        $this->array_values = $lines;

        $this->text_has_changed = true;

    }

    /**
     * @param string $glue
     * @return string Joined lines
     */
    public function join_lines_using( $glue ){

        return implode($glue,$this->array_values);

    }

    /**
     * Find Matching Lines
     * @param $regex
     * @return array
     */
    public function find_matching_lines( $regex ){

        $matches = array();

        foreach( $this->array_values as $line )

            if ( preg_match($regex,$line))

                $matches[] = $line;

        return $matches;
    }

    /**
     * @param $regex
     * @param $content
     */
    public function replace_matching_line_with($regex, $content ){

        $modified = array();

        foreach( $this->array_values as $line ){

            if ( preg_match($regex,$line)){

                $modified[] = $content;

                $this->text_has_changed = true;
            }

            else $modified[] = $line;


        }

        if ( $this->text_has_changed)

            $this->array_values = $modified;


    }

    /**
     * @return string
     */
    public function as_string(){

        return implode("\r\n",$this->array_values);
    }

}