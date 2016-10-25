<?php


namespace Code_Alchemy\Text_Operators;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Text_Template
 * @package Code_Alchemy\Text_Operators
 *
 * A Text Template allows you to quickly bind a set of values (as an array, presumed
 * to be from JSON) to a text template, which can use two types of substitutions:
 *
 * 1) {{curly_braces}}
 * 2) #preceding_hash
 *
 * The result is the template with values replaced and may be converted to a string
 */
class Text_Template extends Stringable_Object{

    public function __construct( $template_text, array $data ){

        // Perform replacements based on a preceeding hash
        if ( preg_match_all("/#([a-z|A-Z|0-9|_]+)/",$template_text,$hits))

            foreach ($hits[1] as $member){

                $template_text = preg_replace("/#$member/",@$data[$member],$template_text);
            }

        // Perform replacements based on braces
        if ( preg_match_all("/\{\{([a-z|A-Z|0-9|_]+)\}\}/",$template_text,$hits))

            foreach ($hits[1] as $member){

                $template_text = preg_replace("/\{\{$member\}\}/",@$data[$member],$template_text);
            }


        // Set as value of Object
        $this->string_representation = (string)$template_text;

    }

}