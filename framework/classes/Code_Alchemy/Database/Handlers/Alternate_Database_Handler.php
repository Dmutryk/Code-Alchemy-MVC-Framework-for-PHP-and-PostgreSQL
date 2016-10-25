<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/21/16
 * Time: 8:53 PM
 */

namespace Code_Alchemy\Database\Handlers;


class Alternate_Database_Handler {

    /**
     * @var null Alternate DB
     */
    private $db  = null;

    public function __construct() {

        $alt_db = @$_SESSION['alternate_database'];

        if ( $alt_db ) {

            $this->db = $alt_db;
        }

    }

    public function db(){ return $this->db; }

}