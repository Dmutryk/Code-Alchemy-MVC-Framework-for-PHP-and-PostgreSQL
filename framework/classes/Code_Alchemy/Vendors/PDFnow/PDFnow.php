<?php


namespace Code_Alchemy\Vendors\PDFnow;

/**
 * Class PDFnow
 * @package Code_Alchemy\Vendors\PDFnow
 */
use Code_Alchemy\Core\Configuration_File;

/**
 * PDFnow library, use it in your own application
 *
 * This file is for calling the API of PDFnow.com
 *
 * Requirements:
 * - PHP 5.3.10
 * - Curl-Library of PHP
 *
 * @author Tobias Haupenthal
 * @version 2014-07-01
 */


class PDFnow {

    /**
     * @var string API Key
     */
    private $api_key = '';

    /**
     * Set API Key
     */
    public function __construct(){

        $this->api_key = (new Configuration_File())->find('pdf-now')['api-key'];

    }

    /**
     * @var string server API endpoint
     */
    private $endpoint = 'pdfnow.com:8080/pdfnow/generate';


    /**
     * Creates a pdf out of a selected template
     * @param String $template - a template, which has been previously uploaded
     * @param Array $array - predefined variables of the template
     * @return array - Status of the request
     *
     * Example:
     * $adresse = array();
     * $adresse[] = array("adresse" => array("name"=>"Thorsten Horn",
     *    "strasse"=>"Meinestr. 26", "plz"=>"52072", "ort"=>"Aachen"));
     *
     * generatePdf('rechnung1', array('addressen' => $addresse));
     *
     *
     * Output
     * On success - temporary URL of the created PDF:
     *   array('status'=> 'OK', 'url' => 'http://')
     * On errror - some error info:
     *   array('status'=> 'NOK', 'error' => 'Template tmpl does not exist.')
     *
     */
    public function generatePdf($template, $array) {
        $xml = $this->array_to_xml($array);
        $xml = str_replace("&", "%26", $xml);
        $fields_string = "apiKey=" . $this->api_key . "&" . "templateName=" . $template . "&" . "xmlString=" . $xml;
        $ch = curl_init();
        $options = array(CURLOPT_URL => $this->endpoint, CURLOPT_RETURNTRANSFER => 1, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => $fields_string);
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $responsedecoded = (array)json_decode($response);
        curl_close($ch);
        return $responsedecoded;
    }

    /**
     * Changes a PHP-Array into an xml construct
     *
     * @param array $array - PHP Array
     * @param object $mxl - predefined variable settings of the template
     * @return \SimpleXMLElement - umgewandeltes XML
     *
     * Example:
     * array_to_xml(array('hallo'=> 'welt'));
     *
     * Output:
     * <hallo>welt</hallo>
     *
     **/
    private function array_to_xml($array, $xml = NULL) {
        $isRootNode = false;
        if (!isset($xml)) {
            $isRootNode = true;
            $xml = new \SimpleXMLElement("<root/>");
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                } else {
                    $this->array_to_xml($value, $xml);
                }
            } else {
                $xml->addChild("$key", "$value");
            }
        }
        if ($isRootNode) {

            $innerXml = ($xml->xpath("/root"));

            $innerXml = $innerXml[0]->children();

            return $innerXml->asXml();
        }
        return $xml;
    }

}