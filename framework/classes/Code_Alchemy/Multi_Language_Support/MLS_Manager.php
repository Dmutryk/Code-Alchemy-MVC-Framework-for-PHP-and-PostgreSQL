<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/26/15
 * Time: 11:58 AM
 */

namespace Code_Alchemy\Multi_Language_Support;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Core\REQUEST_URI;

/**
 * Class MLS_Manager
 * @package Code_Alchemy\Multi_Language_Support
 *
 * MLS Manager is responsible for managing MLS services for the application
 */
class MLS_Manager extends Alchemist{

    /**
     * @var MLS_Settings
     */
    private $settings;

    /**
     * @var bool true if MLS is enabled
     */
    private $is_enabled = false;

    /**
     * @var array routes for which MLS is disabled
     */
    private $disabled_for = array();


    public function __construct(){

        $mls_settings = (new Configuration_File())->find('multi-language-support');

        $this->is_enabled = !! @$mls_settings['enabled'];

        $this->disabled_for = is_array(@$mls_settings['disable-for']) ?

            $mls_settings['disable-for']:array();

        $this->settings = new MLS_Settings(

            is_array($mls_settings)?$mls_settings:array()

        );

    }

    /**
     * @return bool true if MLS is enabled
     */
    public function is_enabled(){

        return $this->is_enabled && ! in_array( (new REQUEST_URI())->part(1),$this->disabled_for);

    }

    /**
     * Validate URI
     */
    public function validate_uri(){

        if ( ! in_array((new REQUEST_URI())->part(1),$this->settings->language_uris()))

            header("Location: /".$this->settings->default_uri().$_SERVER['REQUEST_URI']);

    }

}