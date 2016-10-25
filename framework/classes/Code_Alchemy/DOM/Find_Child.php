<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/20/16
 * Time: 2:03 PM
 */

namespace Code_Alchemy\DOM;


class Find_Child {

    /**
     * @var null
     */
    private $result = null;

    /**
     * Find_Children constructor.
     * @param \DOMElement $element
     * @param $tagName
     * @param null $childNode
     * @param null $childClass
     */
    public function __construct( \DOMElement $element, $tagName, $childNode = null, $childClass = null ) {

        $domlist =  $element->getElementsByTagName($tagName);

        if ( $childNode && $childClass)

            foreach ($domlist as $child ){

                $domlist2 = $child->getElementsByTagName($childNode);

                foreach ( $domlist2 as $child2){

                    $object = $child2->getAttribute('class');

                    if ( $object == $childClass){

                        $this->result = $child;

                        break;
                    }

                }

            }
    }

    /**
     * @return null
     */
    public function result(){ return $this->result; }
}