<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 20/09/14
 * Time: 12:27 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Location_Based;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Configuration_File;

/**
 * Class Geocode_API
 * @package Code_Alchemy\Location_Based
 *
 * Component to interact with Google GeoCode API
 */
class Geocode_API  extends Alchemist{

    // the API endpoint
    const endpoint = 'https://maps.googleapis.com/maps/api/geocode/json';

    /**
     * @var string API Key for Google
     */
    private $api_key = '';

    /**
     * @var bool true if log to firebug
     */
    private $firebug = false;

    /**
     * @var string Last URL used to make an API call
     */
    private $last_url = '';

    /**
     * @param string $api_key
     * @param bool|false $firebug
     */
    public function __construct( $api_key = null, $firebug = false ){

        // save key
        $this->api_key = $api_key ? $api_key : (new Configuration_File())

            ->find('google')['server-key'];

        $this->firebug = $firebug;

    }

    /**
     * Encode an Address
     * @param string $address
     * @return array encoded result from API
     */
    public function encode_address( $address ){

        $url = self::endpoint . '?address=' . $this->prepare_address($address) .
            '&key=' . $this->api_key;

        // Cache it
        $this->last_url = $url;

        if ( $this->firebug ) \FB::log($url);

        $str = file_get_contents($url);

        $components = json_decode( $str, true );

        return $components;

    }

    /**
     * @param string $address
     * @return string replacement
     */
    private function prepare_address( $address ){

        return preg_replace('/\s+/','+',$address);

    }

    /**
     * @return string Last URL
     */
    public function last_url(){

        return $this->last_url;
    }

}