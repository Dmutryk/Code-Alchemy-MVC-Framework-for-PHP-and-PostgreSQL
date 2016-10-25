<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 3/11/16
 * Time: 9:58 PM
 */

namespace Code_Alchemy\Vendors\PHPMailer;


use Code_Alchemy\Core\Array_Object;

/**
 * Class PHPMailer_Credentials
 * @package Code_Alchemy\Vendors\PHPMailer
 *
 * PHP Mailer Credentials
 */
class PHPMailer_Credentials extends Array_Object{

    /**
     * PHPMailer_Credentials constructor.
     * @param string $smtp_server
     * @param $smtp_username
     * @param $smtp_password
     */
    public function __construct( $smtp_server, $smtp_username, $smtp_password ) {

        parent::__construct([

            'smtp_server' => $smtp_server,

            'smtp_username' => $smtp_username,

            'smtp_password' => $smtp_password

        ]);

    }
}