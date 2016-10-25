<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/11/16
 * Time: 6:23 PM
 */

namespace Code_Alchemy\Core;


/**
 * Class Configuration_Fragment
 * @package Code_Alchemy\Core
 *
 * A configuration Fragment
 */
class Configuration_Fragment extends Alchemist {

    /**
     * @var Array_Object to access members
     */
    private $config;

    /**
     * Configuration_Fragment constructor.
     * @param $fragmentKey
     */
    public function __construct( $fragmentKey ) {

        $config = (new Configuration_File())->find( $fragmentKey );

        $this->config = new Array_Object( is_array($config)? $config:[]);

    }

    /**
     * @param $memberName
     * @return mixed
     */
    public function __get( $memberName ){ return $this->config->get($memberName); }
}