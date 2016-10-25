<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/19/16
 * Time: 1:06 PM
 */

namespace Code_Alchemy\Scrapers;

/**
 * Class Dynamic_Website_Connector
 * @package Code_Alchemy\Scrapers
 *
 * Allows you to connect dynamically to a website -- with or without a session -- to read
 * and extract information.
 */
class Dynamic_Website_Connector {

    /**
     * @var bool true to debug, false otherwise
     */
    protected $debug = false;

    /**
     * @var string URL to connect to remote service
     */
    protected $url = '';

    /**
     * @var array Nodes
     */
    private $nodes = [];

    /**
     * @var string Cookie
     */
    private $cookie = '';

    /**
     * @var array of output to display as JSON or in other format
     */
    private $output = [];

    /**
     * Dynamic_Website_Connector constructor.
     * @param array $configuration
     */
    protected function __construct( array $configuration ) {

        $this->url = $configuration['url'];

        $this->nodes = $configuration['nodes'];

        $this->cookie = $configuration['cookie'];

        $this->debug = isset($configuration['debug'])? $configuration['debug']: false;

        if ( $this->debug) \FB::info("Debugging is enabled",get_called_class());

    }

    /**
     * @param string $node
     * @param string $queryString
     * @param bool $dump_result
     * @return string
     */
    protected function getNodeAsText( $node, $queryString = '',$dump_result = false ){

        $ch = curl_init();

        $node = $this->nodes[$node] ? $this->nodes[$node] :'/';

        $endpoint = "$this->url$node?$queryString";

        \FB::info($endpoint,get_called_class());

        curl_setopt ($ch, CURLOPT_URL, $endpoint);

        //curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
        //curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');

        if ( $this->cookie )

            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: $this->cookie"));

        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); /// Follow any redirects

        //curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $result = curl_exec ($ch);

        if ( $dump_result || $this->debug ) \FB::info($result,"cURL Output");

        if (curl_error($ch)) \FB::error(curl_error($ch));

        curl_close($ch);

        return $result;
    }

    /**
     * @param $node
     * @param string $queryString
     * @param bool $dump_result
     * @return \DOMDocument
     *
     * Dump the result
     */
    protected function getNodeAsDom( $node, $queryString = '', $dump_result = false ){

        $dom = new \DOMDocument();

        @$dom->loadHTML($this->getNodeAsText($node,$queryString,$dump_result));

        return $dom;

    }

    /**
     * @return array of output as a result
     */
    public function result(){

        return $this->output;

    }

    /**
     * @return \DOMDocument
     */
    public function categories(){

        $DOMDocument = $this->getNodeAsDom('Home');

        foreach ( $DOMDocument->getElementsByTagName('a') as $tag){

            \FB::info($tag->nodeValue);
        }
        return $DOMDocument;
    }


}