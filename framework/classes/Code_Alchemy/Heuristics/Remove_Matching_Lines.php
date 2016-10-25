<?php


namespace Code_Alchemy\Heuristics;


class Remove_Matching_Lines extends Heuristic_Operator {

    /**
     *
     * @param array $regex_targets
     * @param array $source_lines
     */
    public function __construct( array $regex_targets, array $source_lines ){

        $resulting_lines = array();

        // Foreach source line
        foreach ( $source_lines as $line ){

            $hit = false;

            // Foreach regex
            foreach ( $regex_targets as $target){

                if ( preg_match("/$target/",$line))

                    $hit = true;
            }

            if ( ! $hit ) $resulting_lines[] = $line;
        }

        $this->array_values = $resulting_lines;


    }


}