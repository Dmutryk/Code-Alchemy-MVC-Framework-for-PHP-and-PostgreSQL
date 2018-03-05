<?php namespace Code_Alchemy\APIs\Envato;

use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Configuration_Fragment;
use Code_Alchemy\cURL\cURL_Invocation;

/**
 * Class Envato_API_Client
 * @package Code_Alchemy\APIs\Envato
 *
 * API client to connect to Envato API
 */
class Envato_API_Client extends Alchemist {

    /**
     * @var string Base URL for calling Envato API
     */
    private $baseUrl = 'https://api.envato.com/v1';

    /**
     * @var string Bearer Token
     */
    private $token = '';

    /**
     * @var array result of Operations
     */
    private $result = [];

    /**
     * Envato_API_Client constructor.
     * @param string $apiToken
     */
    public function __construct( $apiToken = '' ) {

        // Get token from configuration
        $this->token = $apiToken ? $apiToken : (new Configuration_Fragment('envato'))->token;

        if ( ! $this->token ){

            $this->result = [ 'result' => 'error', 'error' => 'You need to specify a bearer token in the configuration file'];
        }
    }

    /**
     * Get Categories for a site
     * @param $envatoSiteName
     * @return Envato_API_Client
     */
    public function getSiteCategories( $envatoSiteName ){

        $apiUrl = "$this->baseUrl/market/categories:$envatoSiteName.json";

        $this->result = @json_decode((new cURL_Invocation($apiUrl,[],[

            "Authorization: Bearer $this->token"

        ],'GET'))

            ->result()->as_array()['body'],true);

        return $this;   // for chaining
    }

    /***
     * @param Envato_Search_Parameters $parameters
     * @param bool $isDebug
     * @return Envato_API_Client
     */
    public function searchForItems( Envato_Search_Parameters $parameters, $isDebug = false ){

        $apiUrl = "$this->baseUrl/discovery/search/search/item?$parameters";

        if ( $isDebug ) echo get_called_class().": URL is $apiUrl\r\n";

        $resultAsArray = (new cURL_Invocation($apiUrl, [], [

            "Authorization: Bearer $this->token"

        ], 'GET'))
            ->result()->as_array();

        if ( $isDebug ) {

            var_dump($resultAsArray);

        }

        $this->result = @json_decode($resultAsArray['body'],true);

        return $this;   // for chaining
    }

    /**
     * @return array result
     */
    public function result(){ return $this->result; }

}
