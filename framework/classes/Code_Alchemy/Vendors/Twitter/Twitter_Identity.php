<?php


namespace Code_Alchemy\Vendors\Twitter;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Twitter_Identity
 * @package Code_Alchemy\Vendors\Twitter
 *
 * Represents a Twitter Identity
 */
class Twitter_Identity extends Array_Representable_Object {

    /**
     * @param string $url_or_username to construct
     */
    public function __construct( $url_or_username ){

        if ( preg_match('/http\:\/\/twitter\.com\/([a-zA-Z0-9]+)/',$url_or_username, $hits))

            $this->follow = '@'.$hits[1];

    }

}