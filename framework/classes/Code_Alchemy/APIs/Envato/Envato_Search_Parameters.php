<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/11/16
 * Time: 8:15 PM
 */

namespace Code_Alchemy\APIs\Envato;


use Code_Alchemy\Core\Array_Object;

/**
 * Class Envato_Search_Parameters
 * @package Code_Alchemy\APIs\Envato
 *
 * Envato Search Parameters
 */
class Envato_Search_Parameters extends Array_Object{

    /**
     * Envato_Search_Parameters constructor.
     * @param array $searchParameters
     */
    public function __construct(array $searchParameters ) {

        parent::__construct($searchParameters);

    }

    /**
     * @return string Query string format
     */
    public function toQueryString(){

        $queryString = '';

        foreach ( $this->array_values as $member => $value )

            $queryString .= $queryString ? "&$member=$value": "$member=$value";

        return $queryString;
    }

    /**
     * @return string
     */
    public function __toString() {

        return (string) $this->toQueryString();

    }
}