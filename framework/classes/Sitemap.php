<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 16/06/14
 * Time: 10:54 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class Sitemap {

    /**
     * @var string filename for Sitemap
     */
    private $filename = '';

    /**
     * @var string base url for web locations
     */
    private $base_url = '';

    /**
     * @var \SimpleXMlElement XML of Sitemap
     */
    private $xml;

    /**
     * @param array $options to set when initializing
     */
    public function __construct( $options = array() ){

        foreach ( $options as $member=>$value )

            if ( property_exists( $this, $member ))

                $this->$member = $value;

        $this->xml = simplexml_load_file($this->filename);

    }

    /**
     * Update the Sitemap
     * @param array $locations
     * @return bool
     */
    public function update( $locations = array() ){

        $nodes = $this->xml->children();

        // first check for missing locations and add them
        foreach ( $locations as $location ){

            $found = false;

            foreach ( $nodes as $node){

                if ( (string)$node->loc === $location ){

                    $found = true;

                    break;

                }

            }

            if ( ! $found){

                $new_node = new \SimpleXMLElement('<url></url>');

                $new_node->addChild('loc',$location);

                $child = $this->xml->addChild('url');

                $child->addChild('loc',$location);

            }

        }

        $nodes = @$this->xml->children();

        // now check for extra locations and remove them
        foreach (@$nodes as $node ){

            $found = false;

            foreach ($locations as $location){

                if ( (string)$node->loc === $location ){

                    $found = true;

                    break;

                }


            }

            if ( ! $found ){

                //$node->parentNode->removeChild($node);

                unset($node[0][0]);

            }
        }

        $dom = new \DOMDocument('1.0');

        $dom->preserveWhiteSpace = false;

        $dom->formatOutput = true;

        $dom->loadXML($this->xml->asXML());

        $dom->save($this->filename);

        return true;

    }

}