<?php


namespace Code_Alchemy\Heuristics;


use Code_Alchemy\Themes\Text_Operators\Line_By_Line_Extractor;

class Remove_Inline_Javascript extends Heuristic_Operator {

    /**
     * @param array $nothing
     * @param array $source_lines
     */
    public function __construct(array $nothing = array(), array $source_lines ){

        $resulting_lines = array();

        $in_content = false;

        foreach ( $source_lines as $line ){

            // Does it match start of content?
            if ( preg_match('/\<script\>/',$line) || preg_match('/\<script\s+type\=\"text\/javascript\"\>/',$line) )

                $in_content = true;

            // Save only if not in content
            if ( ! $in_content ) $resulting_lines[] = $line;

            // End of content?
            if ( preg_match('/\<\/script\>/',$line)  )

                $in_content = false;


        }

        $this->array_values = $resulting_lines;

    }

}