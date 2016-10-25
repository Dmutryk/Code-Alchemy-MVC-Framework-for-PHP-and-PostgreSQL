<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/20/16
 * Time: 2:23 PM
 */

namespace Code_Alchemy\DOM;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Concatenate_Node_Values
 * @package Code_Alchemy\DOM
 *
 * Concatenate all Node Values as a single string
 */
class Concatenate_Node_Values extends Stringable_Object{

    /**
     * Concatenate_Node_Values constructor.
     * @param \DOMElement $elem
     * @param $tag_name
     * @param array $skip_values
     * @param array $skip_classes
     */
    public function __construct( \DOMElement $elem, $tag_name, $skip_values = [], $skip_classes = [] ) {

        $value = '';

        foreach ( $elem->getElementsByTagName($tag_name) as $node )

            if ( ! $this->skip($node,$skip_values) && ! $this->skip_classes($node,$skip_classes))

                $value .= (string) $node->nodeValue;

        $this->string_representation = $value;

    }

    /**
     * @param \DOMElement $elem
     * @param array $skip_values
     * @return bool
     */
    private function skip( \DOMElement $elem, array $skip_values ){

        $is_skip = false;

        foreach ( $skip_values as $value )

            if ( preg_match("/$value/",$elem->nodeValue) ){

                \FB::info("Skipping node with value $value");

                $is_skip = true;

                break;
            }

        return $is_skip;

    }

    /**
     * @param \DOMElement $elem
     * @param array $skip_values
     * @return bool true if should be skipped
     */
    private function skip_classes( \DOMElement $elem, array $skip_values ){

        $is_skip = false;

        foreach ( $skip_values as $value )

            if ( $elem->getAttribute('class') == $value ){

                $is_skip = true;

                \FB::info("Skipping node with class $value");

                break;
            }

        return $is_skip;

    }


}