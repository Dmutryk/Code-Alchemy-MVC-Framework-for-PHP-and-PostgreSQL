<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/6/15
 * Time: 12:18 PM
 */

namespace Code_Alchemy\Localization;


use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\JSON\JSON_File;

class Errors_Configuration extends JSON_File {

    /**
     * Create a new instance
     */
    public function __construct() {

        global $webapp_location;

        parent::__construct(array(

            // Set path
            'file_path' => $webapp_location . '/app/config/error-localization.json',

            // Set template
            'template_file' => '/templates/JSON/error-localization.json',

            // Auto create if doesn't exist
            'auto_create' => true,

            // Automatically load file
            'auto_load' => true,

        ));


    }

    /**
     * @param $error_text
     * @return string Localized error
     */
    public function localize( $error_text ){

        $lang = (new Configuration_File())->language();

        foreach ( $this->find('errors') as $array )

            if ( $array['message'] == $error_text && isset( $array[$lang]))

                $error_text = $array[$lang];

        return $error_text;
    }

}