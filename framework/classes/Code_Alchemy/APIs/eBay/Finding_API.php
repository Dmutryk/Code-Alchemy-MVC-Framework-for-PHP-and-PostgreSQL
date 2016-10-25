<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/30/15
 * Time: 7:47 PM
 */

namespace Code_Alchemy\APIs\eBay;


use Code_Alchemy\APIs\Simple_API;
use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Finding_API
 * @package Code_Alchemy\APIs\eBay
 *
 * A convenient way to invoke the eBay Finding API
 */
class Finding_API extends Simple_API{

    public function __construct( array $options ){

        // Set endpoint
        $this->endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';

        // Set parameters
        $this->parameters = array(

            'OPERATION-NAME' =>'findItemsByKeywords',

            'SERVICE-VERSION' =>'1.0.0',

            'SECURITY-APPNAME' => @$options['security_appname'],

            'RESPONSE-DATA-FORMAT' =>'JSON',

            'REST-PAYLOAD' => null,

            'keywords' => @$options['keywords']

        );

        $a = 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=Alquemed-70cf-4145-af1c-c83442e6f95e&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&keywords=harry%20potter%20phoenix';

    }
    

}