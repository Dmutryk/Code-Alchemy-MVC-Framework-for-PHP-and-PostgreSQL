<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/23/15
 * Time: 12:31 PM
 */

namespace Code_Alchemy\HTTP;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Send_CORS_Headers
 * @package Code_Alchemy\HTTP
 *
 * Sends CORS headers
 */
class Send_CORS_Headers extends Alchemist{

    /**
     * @param string $origin to allow
     */
    public function __construct( $origin = '*' ){

        // Allow CORS
        if (isset($_SERVER['HTTP_ORIGIN'])) {

            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            header("Access-Control-Allow-Headers: *");

        }

        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Access-Control-Allow-Origin');

    }
}