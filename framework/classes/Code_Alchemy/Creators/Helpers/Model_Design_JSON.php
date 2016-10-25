<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/27/16
 * Time: 4:18 PM
 */

namespace Code_Alchemy\Creators\Helpers;


use Code_Alchemy\JSON\JSON_File;

/**
 * Class Model_Design_JSON
 * @package Code_Alchemy\Creators\Helpers
 *
 * Represents design of Models from JSON
 */
class Model_Design_JSON extends JSON_File{

    /**
     * Model_Design_JSON constructor.
     * @param string $filename
     */
    public function __construct( $filename ) {


        parent::__construct([

            'auto_load' => true,

            'file_path' => getcwd() ."/$filename"

        ]);

    }

    /**
     * @return array of Model specifications
     */
    public function models(){

        return (array) $this->find('models',true);

    }

    /**
     * @return \stdClass MLS Settings
     */
    public function mls_settings(){

        return $this->find('settings',true)->mls;

    }
}