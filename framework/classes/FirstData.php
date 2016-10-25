<?php
/**
 * Implementation of FirstData GlobalGateway, REST API over cURL
 */

namespace Code_Alchemy\integrations\apis;
use Code_Alchemy\behaviors;
use Code_Alchemy\components;

class FirstData implements behaviors\transactable {

    // these members are all required to post transactions to the Gateway server
    private $host = "";
    private $protocol = "";
    private $uri = "";
    private $hmackey = "";
    private $keyid = "";
    private $gatewayid = "";
    private $password = "";


    public function __construct( $options ){

        if ( ! is_array( $options ))
            throw new \IllegalArgumentException(get_class(). ": First argument must be an array of options");

        foreach ( $options as $name=>$value )
            if ( property_exists( get_class(), $name))
                $this->$name = $value;



    }

    public function post_transaction( $transaction, $verbose = false ){

        // set location
        $location = $this->protocol . $this->host . $this->uri;

        if ( $verbose ) echo "Location is $location\r\n";

        // sign transaction
        $transaction->sign( $this->gatewayid, $this->password );

        // encode it
        $content = json_encode($transaction->as_array());


        $old_tz = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $gge4Date = strftime("%Y-%m-%dT%H:%M:%S", time()) . 'Z';
        date_default_timezone_set($old_tz);
        $contentType = "application/json";
        $contentDigest = sha1($content);
        $contentSize = sizeof($content);
        $method = "POST";

        $hashstr = "$method\n$contentType\n$contentDigest\n$gge4Date\n$this->uri";

        $authstr = 'GGE4_API ' . $this->keyid . ':' . base64_encode(hash_hmac("sha1", $hashstr, $this->hmackey, true));

        $headers = array(
            "Content-Type: $contentType",
            "X-GGe4-Content-SHA1: $contentDigest",
            "X-GGe4-Date: $gge4Date",
            "Authorization: $authstr",
            "Accept: $contentType"
        );

        //CURL stuff
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $location);

        //Warning ->>>>>>>>>>>>>>>>>>>>
        /*Hardcoded for easier implementation, DO NOT USE THIS ON PRODUCTION!!*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //Warning ->>>>>>>>>>>>>>>>>>>>

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        //This guy does the job
        $output = curl_exec($ch);

        if ( $verbose ) echo "CURL error: ".curl_error($ch)."\r\n";

        //echo curl_error($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = $this->parseHeader(substr($output, 0, $header_size));
        $body = substr($output, $header_size);

        curl_close($ch);
        //Print the response header
        //var_dump($header);

        $res = array();
        /* If we get any of this X-GGe4-Content-SHA1 X-GGe4-Date Authorization
         * then the API call is valid */
        if (isset($header['authorization']))
        {
            //Ovbiously before we do anything we should validate the hash
            //var_dump(json_decode($body));
            $res = json_decode($body,true);

            if ( $verbose ) {
                echo "Dump of raw result from API appears next<br>\r\n";
                var_dump( $res );
            }
        }
        //Otherwise just debug the error response, which is just plain text
        else
        {

            if ( $verbose ) echo "Body is $body\r\n";

            $res = array(
                'result'=>'error',
                'error'=>$body
            );
        }


        $real_error = isset($res['exact_message'])?$res['exact_message']:
            (isset($res['bank_message'])?$res['bank_message']:'Error');

        $result = new components\TransactionResult(array(
            'receipt'=>$res['ctr'],
            'sequence_no'=>$res['sequence_no'],
            'result'=> $this->parse_result($real_error,@$res['bank_message']),
            'is_approved'=>isset($res['transaction_approved'])?$res['transaction_approved']:0,
            'authorization_number'=>$res['authorization_num'],
            'error'=> $this->parse_error( $real_error, $res['error'], @$res['bank_message'])
        ));

        return $result;

    }

    /**
     * Parse the error
     * @param $result
     * @param $err
     * @return string
     */
    private function parse_error( $result,$err,$bank_message = null){

        $error = '';

        /**
         * Special case
         */
        if ( $bank_message == 'Invalid CC Number')
            $error = "Card number is invalid";

        else {
            switch( $result){

                case 'Card is expired':

                    $error = 'Invalid expiration date, or '.$result;
                    break;
                case 'Invalid CC Number':
                    $error = 'Invalid card number';
                    break;
                default:

                    /**
                     * Clean up error for invalid CVV2
                     */
                    if ( preg_match('/Bad Request \(08\) \- CVV2\/CID\/CVC2 Data not Verified/',$err)){

                        $error = 'Either card expiration date or security code (cvv2) is invalid';

                    } else $error = $result;
                    break;
            }

        }


        if ( $error == 'Transaction Normal') {
            $error = $bank_message == 'Restraint'?'Transaction not approved':'';
        }
        return $error;

    }

    /**
     * Parse the result from the server
     * @param $str
     * @param null $bank_message
     * @return string
     */
    private function parse_result( $str, $bank_message = null){

        $result = '';

        switch ( $str ){
            case 'Address not Verified':
            case 'Invalid CC Number':
            case 'Card is expired':
                $result = 'Error';
            break;
            case 'Transaction Normal':
                /**
                 * Hang on!  Normal transaction doesn't mean it's ok
                 */
                if ( $bank_message && in_array($bank_message,array('Invalid CC Number','Restraint')))
                    $result = 'Error';
                else $result = 'Approved';
            break;
            default:
                $result = $str;
            break;
        }

        return $result;
    }

    private function parseHeader($rawHeader)
    {
        $header = array();

        //http://blog.motane.lu/2009/02/16/exploding-new-lines-in-php/
        $lines = preg_split('/\r\n|\r|\n/', $rawHeader);

        foreach ($lines as $key => $line)
        {
            $keyval = explode(': ', $line, 2);

            if (isset($keyval[0]) && isset($keyval[1]))
            {
                $header[strtolower($keyval[0])] = $keyval[1];
            }
        }

        return $header;
    }
}