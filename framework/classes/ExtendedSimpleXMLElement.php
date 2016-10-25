<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 15/06/14
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class ExtendedSimpleXMLElement extends \SimpleXMLElement {

    /**
     * Add SimpleXMLElement code into a SimpleXMLElement
     *
     * @param MySimpleXMLElement $append
     */
    public function appendXML( ExtendedSimpleXMLElement $append)
    {
        if ($append) {
            if (strlen(trim((string)$append)) == 0) {
                $xml = $this->addChild($append->getName());
            } else {
                $xml = $this->addChild($append->getName(), (string)$append);
            }

            foreach ($append->children() as $child) {
                $xml->appendXML($child);
            }

            foreach ($append->attributes() as $n => $v) {
                $xml->addAttribute($n, $v);
            }
        }
    }

}