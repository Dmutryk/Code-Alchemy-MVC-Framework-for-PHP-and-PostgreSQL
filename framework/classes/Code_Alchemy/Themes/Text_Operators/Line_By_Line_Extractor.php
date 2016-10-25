<?php


namespace Code_Alchemy\Themes\Text_Operators;


class Line_By_Line_Extractor {

    /**
     * @var array of Extractions
     */
    private $extracted_content = array();

    /**
     * @var array of specifications
     */
    private $specifications = array();

    /**
     * @var array nested level for this hit
     */
    private $nested_level = array();

    /**
     * @var bool true to be verbose
     */
    private $verbose = false;

    /**
     * @param array $options to set by user
     */
    public function __construct( array $options = array() ){

        foreach ( $options as $name => $value )

            if ( property_exists($this,$name) )

                $this->$name = $value;
    }

    /**
     * Extract content based on a spec
     * @param string $content_label
     * @param array $specification
     */
    public function extract( $content_label, $specification ){

        // Initialize
        $this->extracted_content[ $content_label ] = '';

        // Set spec
        $this->specifications[ $content_label ] = $specification;

        // Initialize level
        $this->nested_level[ $content_label ] = 0;

    }

    /**
     * Consume and process a line of text
     * @param string $line_of_text
     * @return string
     */
    public function consume( $line_of_text ){

        // Result is textual
        $result = '';

        // For each specification
        foreach ( $this->specifications as $label => $spec ){

            $regex = "/" . $spec['end'] . "/";

            // Sometimes we need to skip nest down check
            $skip_nest_down_check = false;



            // if hits start
            $nest = "/" . $spec['nest'] . "/";

            // Skip nest down check when matches both
            if (

                $this->nested_level[$label] &&

                preg_match($nest,$line_of_text) &&

                preg_match($regex,$line_of_text)

            )

                $skip_nest_down_check = true;


            if ( preg_match("/".$spec['start']."/",$line_of_text)){

                if ( $this->verbose )

                    echo get_called_class().": Consumed a line of text that matches start ".$spec['start']."\r\n";

                $result = "start:$label";

                // Go up one level
                $this->nested_level[$label]++;

                // Add it to extracted
                $this->extracted_content[$label] .= $line_of_text;

            }

            // If hits nest and we-re stil inside
            elseif (

                $this->nested_level[$label] &&

                preg_match($nest,$line_of_text)


            ){

                // if also matches nest down in same line
                if ( preg_match($regex,$line_of_text)){

                    // Skip nest down check
                    $skip_nest_down_check = true;

                } else {

                    // Go up one level
                    $this->nested_level[$label]++;

                    if ( $this->verbose )

                        echo get_called_class().": Consumed a line of text that nests up the level".$spec['nest']."New level is ".$this->nested_level[$label]."\r\n";


                    // Add it to extracted
                    $this->extracted_content[$label] .= $line_of_text;

                    // echo "+nested".$this->nested_level[$label]."\r\n";


                }



            }


            // If hits end
            elseif (

                ! $skip_nest_down_check &&
                $this->nested_level[$label]
                && preg_match($regex,$line_of_text)){


                // Go down one level
                $this->nested_level[$label]--;

                if ( $this->verbose )

                    echo get_called_class().": Consumed a line of text that nests DOWN the level $regex. New level is ".$this->nested_level[$label]."\r\n";

                // Add it to extracted
                $this->extracted_content[$label] .= $line_of_text;

                // Back to level 0?
                if ( ! $this->nested_level[$label] ){

                    // indicate we're at end
                    $result = "end:$label";

                    if ( $this->verbose )

                        echo get_called_class().": We've hit the end of this extractable content\r\n";
                }



            } else {

                // If inside
                if ( $this->nested_level[$label])

                    // Just add it
                    // Add it to extracted
                    $this->extracted_content[$label] .= $line_of_text;

            }

        }

        return $result;

    }

    /**
     * @param $label
     * @return string extracted content
     */
    public function extraction( $label ){

        return isset($this->extracted_content[$label])? $this->extracted_content[$label]:'';

    }

}