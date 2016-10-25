<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/30/16
 * Time: 12:32 PM
 */

namespace Code_Alchemy\Applications\Toolboxes;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Themes\Helpers\HEAD_Section_Normalizer;

class Toolkit extends Alchemist {

    public function __construct( $tool_name, $root_path, array $arguments) {

        echo "Code Alchemy Toolkit\r\n";

        if ( $tool_name ) $this->$tool_name();

        else $this->usage();

    }

    /**
     * Normalize the HTML head
     */
    private function normalize_html_head(){

        foreach ( (new HEAD_Section_Normalizer())->as_array() as $result)
        {

        }

    }
    /**
     * Show usage
     * @param string $tool
     */
    private function usage( $tool = '' ){

        echo ($tool ? "$tool:":'').  "Unknown tool or none specified\r\n"

        ."Available tools:\r\n".
            "\tnormalize_html_head\tExtracts common part of each head file into a shared file\r\n"
        ;

    }

    public function __call( $tool, $args ){


    }

}