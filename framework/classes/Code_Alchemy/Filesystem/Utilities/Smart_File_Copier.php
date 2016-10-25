<?php


namespace Code_Alchemy\Filesystem\Utilities;


use Code_Alchemy\Filesystem\Helpers\One_Level_Up;

class Smart_File_Copier {

    private $source = '';

    private $destination = '';

    private $replacements = array();

    private $is_overwrite = false;

    private $add_to_git = true;

    /**
     * @var string Last Error
     */
    public $error = '';

    /**
     * @param $source
     * @param $destination
     * @param $replacements
     * @param $is_overwrite
     */
    public function __construct( $source, $destination, $replacements, $is_overwrite ){

        $this->source = $source;

        $this->destination = $destination;

        $this->replacements = $replacements;

        $this->is_overwrite = $is_overwrite;

    }

    /**
     * @param bool|false $verbose to send output to screen
     * @return bool
     */
    public function copy( $verbose = false ){

        $result = false;

        if ( $verbose ) echo "\t". get_called_class().": Smart copying $this->source to $this->destination\r\n";

        if ( ! file_exists($this->destination) || $this->is_overwrite ){

            // Open source
            $in = fopen ( $this->source, "r");

            if ( $in ){

                $destination_directory = (string) new One_Level_Up($this->destination);

                if ( file_exists($destination_directory) && is_dir($destination_directory)){

                    // Open dest
                    $out = @fopen( $this->destination, "w");

                    if ( $out ){

                        // read in xml as a string
                        while ( $data = fgets( $in ) ) {
                            // replace app name
                            foreach( $this->replacements as $reg => $rep )

                                $data = preg_replace( $reg , $rep , $data );

                            fputs( $out, $data );

                        }

                        fclose( $in);

                        fclose( $out);

                        $result = true;

                        shell_exec("git add $this->destination >/dev/null 2>/dev/null");

                    } else {

                        \FB::warn(get_called_class().": $this->destination: Unable to open file for writing");
                    }

                } else {

                    $result = false;

                    $this->error = "$destination_directory: No such directory exists";

                    \FB::warn(get_called_class().": $this->error");
                }
            } else {

                \FB::warn(get_called_class().": Unable to open Source file for reading");
            }

        } else $this->error = "\t$this->destination: File exists and not allowed to overwrite";


        return $result;

    }

}