<?php

namespace Code_Alchemy\Vendors\Koala_Trends;


use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Scrapers\Dynamic_Website_Connector;


/**
 * Class Koala_Trends_Connector
 * @package Code_Alchemy\Vendors\Koala_Trends
 *
 * Connects dynamically to Koala Trends Website, to extract data for deposit into a local database
 */
class Koala_Trends_Connector extends Dynamic_Website_Connector{

    /**
     * Koala_Trends_Connector constructor.
     *
     * Construct a new component to connect to a specific instance of Koala Trends
     */
    public function __construct() {

        $configuration = (new Configuration_File())->find('koala-trends');

        parent::__construct($configuration);

        $this->url = "http://".strtolower($configuration['country']).".koalatrends.com";

    }

    /**
     * Get the node as a text dump
     */
    public function toScreen(){

        header('Content-Type: text/html');

        $nodeAsText = $this->getNodeAsText('Home');



        echo (utf8_encode($nodeAsText));

    }
}