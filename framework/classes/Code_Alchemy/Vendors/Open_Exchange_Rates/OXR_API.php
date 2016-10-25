<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/27/16
 * Time: 3:38 PM
 */

namespace Code_Alchemy\Vendors\Open_Exchange_Rates;

use Code_Alchemy\APIs\Scaffolding\REST_API_Client;

/**
 * Class OXR_API
 * @package Code_Alchemy\Vendors\Open_Exchange_Rates
 *
 * A simple API for Open Exchange Rates
 */
class OXR_API extends REST_API_Client {

    public function __construct() {

        parent::__construct( 'open-exchange', [ 'app_id'] );

    }

    public function convert( $from_currency, $to_currency, $amount ){

        $latest = $this->_latest();

        $rate = 1;

        if ( $from_currency == 'USD' ) {

            $rate = $amount * $latest->rates->$to_currency;

        } else {

            $dollars_amount = $this->_to_dollars( $amount, $from_currency, $latest->rates );

            $rate = $dollars_amount * $latest->rates->$to_currency;

        }

        return $rate;

    }

    /**
     * @param $amount
     * @param $from_currency
     * @param \stdClass $rates
     * @return float
     */
    private function _to_dollars( $amount, $from_currency, \stdClass $rates ){

        return $amount / $rates->$from_currency;


    }

    /**
     * @return array of latest exchange rates
     */
    private function _latest(){

        return $this->_get_node( 'latest.json' ,[]);

    }

}