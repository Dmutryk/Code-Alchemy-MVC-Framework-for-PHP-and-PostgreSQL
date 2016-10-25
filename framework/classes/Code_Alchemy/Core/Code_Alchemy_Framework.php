<?php


namespace Code_Alchemy\Core;


use Code_Alchemy\Security\Officer;

/**
 * Class Code_Alchemy_Framework
 * @package Code_Alchemy\Core
 *
 * The heart of the framework, has some core methods and directives to run
 * any web application
 */
class Code_Alchemy_Framework {

    /**
     * @var self $instance pointer to the one-and-only, once instantiated
     */
    private static $instance = null;

    /**
     * @var string $last_error from any operation, when applicable
     */
    public $last_error = '';

    /**
     * @var string $platform that the system iis running on, such as Linux
     */
    private $platform = '';

    /**
     * @var Configuration_File
     */
    private $json_configuration;

    /**
     * Construct the X-Objects Container.  May only be done once privately per page
     * session.
     */
    private function __construct() {

        global $webapp_location;    // required for various settings

        // New! Configuration loads as JSON
        $this->json_configuration = new Configuration_File();

        $tz = @date_default_timezone_get();

        $this->timezone = $tz;

        $this->platform = preg_match( '/;/' , ini_get( "include_path" ) ) ? "win" : "ux";

    }


    /**
     * Get a magic member
     * @param $what string the name of the member
     * @return mixed the member value
     */
    public function __get ( $what ) {

        $tag = new \xo_codetag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        $s = new \SESSION();

        switch ( $what ) {

            // get a production token
            case 'prod_token':
                return (string)$this->xml->site->environment === 'production'?'prod':'';
                break;
            // are we running from command line?
            case 'is_cli':
                return php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR']);
                break;
            // get the timezone offset
            case 'tz_offset':
            case 'timezone_offset':
                $date = new \DateTime("now",new \DateTimeZone( $this->timezone));
                return $date->getOffset()/3600;
            case 'debug':
                return false;
                break;
            case 'debug_level':
                return 0;
                break;
            default:
                if ( ! isset( $this->$what )) {
                    //$msg="<span style=\"color: red;\">$tag->event_format : The application code attempted to access an undefined property <span style=\"font-weight:bold;color:green\">'$what'</span></span>";
                    //trigger_error( $msg, E_USER_WARNING);
                    return false;
                } else
                    return $this->$what;
                break;
        }
    }

    //! get the current browser as a text token
    public function browser() {

        return $this->services->utilities->browser();
    }

    /**
     * @return Code_Alchemy_Framework the instance
     */
    public static function instance() {

        if (!isset(self::$instance))

            self::$instance = new Code_Alchemy_Framework();

        return self::$instance;

    }

    // Prevent users to clone the instance
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }


    /*! get a specific app
    \param $name (string) The name of the app
    \returns new WebApp
    \throws Exception on errors
    */
    public function app ( $name ) {

        return new \WebApp( $name );

    }

    //! return a given service object instance
    public function service( $name ) {

        return $this->service[$name];

    }

    //! translate a key to a classname
    public function key_to_class( $key ) {

        $arr = explode( '-', $key);

        foreach ( $arr as $key => $val ) {

            switch ( (string) $this->config->xml()->classname_case ) {

                case 'all_lowercase':

                    $arr[ $key ] = strtolower( $val );

                    break;

                default:

                    $arr[ $key ] = ucfirst( $val );

                    break;

            }
        }

        return implode( '', $arr );

    }

    //! magic call... woo hoo :)
    public function __call($what,$args){
        switch($what ){
            default:
                echo( "<span style=\"color:red\">An attempt was made to call an undefined function <span style=\"color: green\">$what</span></span>");
                break;
        }

    }

    public function platform(){
        return $this->platform;
    }

    /**
     * css style for showing a banner with the label of the environment
     */
    public function environment_banner_style(){
        $style = 'display:none;';
        $settings = $this->config->xml()->site->environment;
        if ($settings)
            $style = $settings->display == 'yes'?'display:block':'display:none';
        return $style;
    }

    public function environment_label(){
        $label = 'Environment: Unknown';
        $settings = $this->config->xml()->site->environment;
        if ($settings)
            $label = $settings->label?'Environment: '.(string)$settings->label : 'Environment: unknown';
        return $label;
    }

    /**
     * Get the debug status as a numeric value
     * @return int the status
     */
    public function debug_status(){
        return $this->debug_manager->status();
    }

    /**
     * @return string the Web Root
     */
    public function webroot(){

        global $webapp_location;

        return (string) $webapp_location;

    }

    /**
     * @return bool true if we're using CMS
     */
    public function using_cms(){

        return !! $this->cms_broker;

    }

    /**
     * @return SimpleXMLElement Site Configuration
     */
    public function configuration(){


        return $this->json_configuration;

    }

    /**
     * @return Officer
     */
    public function security_manager(){

        $namespace = $this->configuration()->appname;

        $security_class = "\\$namespace\\beans\\security_manager";


        global $autoload_bypass_exception;

        $autoload_bypass_exception = true;

        return class_exists( $security_class )?

            new $security_class:

                new Officer();


    }

    /**
     * @return string Language
     */
    public function language(){

        return (new Configuration_File())->language();

    }

}