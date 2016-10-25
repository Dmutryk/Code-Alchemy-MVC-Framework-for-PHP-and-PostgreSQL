<?php
/**
 * Created by David Greenberg <david@alquemedia.com>.
 * Date: 7/27/16
 * Time: 3:54 PM
 */

namespace Code_Alchemy\APIs\Yellow_Pages;



use Code_Alchemy\Core\Alchemist;

/**
 * Class Yellow_Pages_API
 * @package Code_Alchemy\APIs\Yellow_Pages
 *
 * Yellow Pages API
 */
class Yellow_Pages_API extends Alchemist{

    /**
     * @param YP_Search_Term $search_Term
     * @return YP_Search_Result
     */
    public function search( YP_Search_Term $search_Term ){

        $config = new YP_Configuration();

        $ch = curl_init();

        $term = preg_replace('/\s+/','+',$search_Term->term);

        $url = "$config->endpoint?key=$config->key&format=json&term=$term&searchloc=$search_Term->location";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($ch);

        return new YP_Search_Result(json_decode($output,true));

    }
}