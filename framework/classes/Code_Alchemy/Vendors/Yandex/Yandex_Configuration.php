<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 7/27/16
 * Time: 4:35 PM
 */

namespace Code_Alchemy\Vendors\Yandex;


use Code_Alchemy\Core\Configuration_File;

class Yandex_Configuration {

    /**
     * @var string
     */
    public $direction = '';

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

        $config = (new Configuration_File())->find('yandex');

        $this->endpoint = $config['endpoint'];

        $this->key = $config['key'];

        $this->direction = $config['direction'];


    }

}