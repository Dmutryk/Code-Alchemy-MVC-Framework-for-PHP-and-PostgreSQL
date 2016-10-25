<?php
/**
 * User: "David Owen Greenberg" <code@x-objects.org>
 * Date: 13/02/13
 * Time: 10:18 AM
 */
abstract class xo_rest_api implements unit_testable {

    /**
     * @var bool debug if set to true
     */
    protected $debug = false;

    protected $last_error = "";    // last error from any operation
    protected $end_point = "";  // end point for API calls
    // run all required tests
    public function test_all(){
        $result = true;
        if ( ! $this->end_point){
            $this->last_error = "An end point must be defined";
            $result = false;
        } else {
            // test self
            $result = $this->test_self();
            if ( $result)
                foreach ( $this->get_tests() as $method){
                    if ( ! method_exists($this,$method)){
                        $this->last_error = "Test method $method doesn't exist";
                        return false;
                    } else {
                        $result &= $this->$method();
                        if ( !$result) break;
                    }
                }
        }
        return $result;
    }
    public function get_error(){ return $this->last_error; }

    /**
     * @param $url
     * @param string $where
     * @param array $headers
     * @param bool $debug if true prints debugging output
     * @return mixed
     */
    public function do_get($url, $where = '',$headers= array(),$debug = false, $includes = '' ){

        $tag = new xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        if ( $where ){

            $url = $url.'?'.$where;

            if ( $debug ) echo "$tag->event_format: url = $url<br>\r\n";

            //echo $url;

        }

        if ( $includes ){

            $url .= ($where?'&':'?').'include='.$includes;

        }

        //var_dump($url);

        // get the curl session object
        $session = curl_init($url);

        // set the GET options.
        curl_setopt($session, CURLOPT_POST, false);



        /**
         * Set the HTTP headers as specified
         */
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);


        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        /**
         * For now we want to stop verifying the SSL cert
         */
        curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);

        return $this->do_curl($session,$debug);

    }

    /**
     * Do a POST operation
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function do_post($url, $params=array(), $headers =array()) {

        // get the curl session object
        $session = curl_init($url);

        // set the POST options.
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($session, CURLOPT_HEADER, false);
        /**
         * For now we want to stop verifying the SSL cert
         */
        curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);

        /**
         * Set the HTTP headers as specified
         */
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

        /**
         * For now we want to stop verifying the SSL cert
         */
        curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);



        return $this->do_curl($session);
    }

    /**
     * @param $session
     * @param bool $debug if true, prints debug output
     * @return mixed
     */
    private function do_curl($session, $debug = false){

        $tag = new xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        // do the POST and then close the session
        $response = curl_exec($session);

        if ( $debug ){

            echo "$tag->event_format: raw response appears below<br>\r\n";

            var_dump( $response );

        }

        if(curl_errno($session)) {
            echo 'error:' . curl_error($session);
        }

        curl_close($session);

        return $response;
    }



}
