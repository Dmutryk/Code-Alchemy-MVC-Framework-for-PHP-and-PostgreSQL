<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 7/31/16
 * Time: 8:24 PM
 */

namespace Code_Alchemy\Vendors\Yandex;


use Code_Alchemy\APIs\Scaffolding\Web_Services_API_Client;
use Code_Alchemy\Core\Alchemist;

/**
 * Class Yandex_API_Client
 * @package Code_Alchemy\Vendors\Yandex
 */
class Yandex_API_Client extends Alchemist{

    /**
     * Yandex_API_Client constructor.
     */
    public function __construct() {

    }

    /**
     * @param $text
     * @param string $direction
     * @return mixed
     */
    public function translate( $text, $direction = '' ){

        $config = new Yandex_Configuration();

        $ch = curl_init();

        $term = preg_replace('/\s+/','+',$text);

        $direction1 = $direction ? $direction : $config->direction;

        $url = "$config->endpoint".'translate'."?key=$config->key&lang=$direction1&text=$text";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($ch);

        return json_decode($output,true);

    }

    /**
     * @param array $strings
     * @param $direction
     * @return array
     */
    public function translate_array( array $strings, $direction ){

        $result = [];

        foreach ( $strings as $name => $value )

            $result[ $name ] = $this->translate($value, $direction)['text'][0];

        return $result;

    }

    public function get_languages( $filter_from = ''){

        $config = new Yandex_Configuration();

        $ch = curl_init();

        $url = "$config->endpoint".'getLangs'."?key=$config->key";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($ch);

        $json_decode = json_decode($output, true);

        if ( $filter_from ){

            $res = [

                'dirs'
            ];

            foreach ( $json_decode['dirs'] as $lang)

                if ( preg_match("/^$filter_from-/",$lang))

                    $res['dirs'][] = $lang;

            $json_decode = $res;
        }
        return $json_decode;

    }

}