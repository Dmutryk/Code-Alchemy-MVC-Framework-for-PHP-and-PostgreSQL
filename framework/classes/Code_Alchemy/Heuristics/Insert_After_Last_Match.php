<?php


namespace Code_Alchemy\Heuristics;


class Insert_After_Last_Match extends Heuristic_Operator{

    public function __construct(
        array $inserted_lines,
        array $source_lines,
        array $options
    ){

        // Get some matches
        $matches = isset( $options['match'])?array($options['match']):$options['matches'];

        $resulting_lines = array();

        $is_matching = false;

        foreach( $source_lines as $line){

            // Are we currently matching?
            if ( $is_matching ){

                // By Default, no match
                $is_matched = false;

                // Foreach match
                foreach( $matches as $match ){

                    // If we got a hit
                    if ( preg_match("/$match/",$line)){

                        // We are matched
                        $is_matched = true;

                    }


                }

                if ( ! $is_matched) {

                    //echo "inserting_lines!\r\n";

                    // Ok, so insert those lines
                    $resulting_lines = array_merge($resulting_lines,$inserted_lines);

                    // No longer matching
                    $is_matching = false;

                }

            } else {

                $is_matched = false;

                // Foreach match
                foreach( $matches as $match ){

                    // If we got a hit
                    if ( preg_match("/$match/",$line)){

                        //echo "Matched!\r\n";


                        // We are matched
                        $is_matched = true;

                    }


                }


                // We're now matching
                if ( $is_matched ) $is_matching = true;

            }

            // Add line in any case
            $resulting_lines[] = $line;

        }

        // Save result
        $this->array_values = $resulting_lines;
    }

}