<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 7/27/16
 * Time: 4:35 PM
 */

namespace Code_Alchemy\APIs\Yellow_Pages;


use Code_Alchemy\Core\Configuration_File;

class YP_Configuration {

    /**
     * @var string
     */
    public $endpoint = '';

    /**
     * @var string
     */
    public $key = '';

    /**
     * YP_Configuration constructor.
     */
    public function __construct() {

        $config = (new Configuration_File())->find('yellow-pages-api');

        $this->endpoint = $config['search-endpoint'];

        $this->key = $config['key'];


    }

}