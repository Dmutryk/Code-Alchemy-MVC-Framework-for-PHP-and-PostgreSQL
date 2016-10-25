<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/4/15
 * Time: 6:43 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Helpers;


use Code_Alchemy\JSON\JSON_File;

/**
 * Class Payment_Configuration_File
 * @package Code_Alchemy\Payment_Alchemy\Helpers
 *
 * Configuration file for Payment Alchemy
 */
class Payment_Configuration_File extends JSON_File {

    public function __construct(){

        global $webapp_location;

        if ( ! $webapp_location ) $webapp_location = getcwd();

        parent::__construct( array(

            // Set path
            'file_path' => $webapp_location.'/app/config/payment-alchemy.json',

            // Set template
            'template_file' => '/templates/JSON/payment-alchemy.json',

            // Auto create if doesn't exist
            'auto_create'=>true,

            // Automatically load file
            'auto_load'=>true

        ));



    }

}