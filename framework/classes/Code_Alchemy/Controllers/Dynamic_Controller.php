<?php


namespace Code_Alchemy\Controllers;


use Code_Alchemy\AngularJS\Helpers\AngularJS_Request_Data;
use Code_Alchemy\Controllers\Actors\Run_Custom_Controller;
use Code_Alchemy\Controllers\Helpers\Controller_Key;
use Code_Alchemy\Controllers\Helpers\Custom_Controller_Class;
use Code_Alchemy\Controllers\Helpers\Custom_Controller_Replacements;
use Code_Alchemy\Controllers\Helpers\Custom_Data_Fetcher_Class;
use Code_Alchemy\Controllers\Helpers\Is_Production_Host;
use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\REQUEST_URI;
use Code_Alchemy\Directors\Forms_Submissions_Director;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Localization\Current_Language;
use Code_Alchemy\Multi_Language_Support\MLS_Manager;
use Code_Alchemy\Security\Officer;

class Dynamic_Controller extends Application_Controller{

    /**
     * @var Controller_Configuration_File
     */
    private $config;

    /**
     * @var array of Post data
     */
    private $post_data = array();

    /**
     * @var array of substitutions for query strings
     */
    private $query_substitutions = array();


    /**
     * Create a new Dynamic Controller
     * @param array $post_data sent from client
     */
    public function __construct( array $post_data ){

        // Fetch AngularJS data if any
        $angular_data = (new AngularJS_Request_Data())->as_array();

        // Merge in AngularJS data
        $this->post_data = array_merge($post_data,

            is_array($angular_data)?$angular_data:[]);


        $this->config = new Controller_Configuration_File();

        parent::__construct();

    }


    /**
     * Go, which means run the Controller
     */
    public function go(){

        $route = $this->config->get_route();

        // Set layout first, so Custom Controller can override it
        $this->layout = @$route['layout'];

        $this->theme = @$route['theme'];

        $this->theme_root = @$route['theme-root'];

        // If logging out
        if ( in_array($this->uri()->part(1),array('logout','signout','quit','leave','salir'))){

            $mgr = new Officer();

            $mgr->logout();

            header('Location: /');
        }

        // If we have a data spec
        if ( isset($route['data'])){

            // So user can indicate this in query search
            $this->query_substitutions['layout_name'] = $route['layout'];

            // For an array of data
            if ( is_array($route['data'])){

                // Get fetcher
                $fetcher = new Data_Fetcher($route['data'],
                $this->query_substitutions,
                    $this->data
                );

                $this->data = array_merge($this->data,$fetcher->as_array());
            } else {


                $class = (string) new Custom_Data_Fetcher_Class($route['data']);

                if ( class_exists( $class )){

                    $fetcher = new $class;

                    if ( method_exists($fetcher,'as_array'))

                        $this->data = array_merge($this->data,$fetcher->as_array());

                }

            }

        }


        // Global data specification
        if ( $this->config->find('data')){

            // So user can indicate this in query search
            $this->query_substitutions['layout_name'] = @$route['layout'];


            $this->data = array_merge( $this->data,
                (new Data_Fetcher(
                    $this->config->find('data'),
                    $this->query_substitutions,
                    $this->data

                ))->as_array()
            );



        }


        // run the user's custom Controller code
        if ( isset($route['controller']))

            new Run_Custom_Controller($route,$this->data,$this->post_data,$this->layout);

        // MLS support
        if ( (new MLS_Manager())->is_enabled())

            $this->data = array_merge($this->data,array(

                'current_MLS_lang' => (string) new Current_Language()

            ));

        // Lastly Runa a Master Custom Controller
        new Run_Custom_Controller(array(

            'controller' => 'Master_Custom_Controller',

            'type' => 'master'

        ),$this->data,$this->post_data,$this->layout);

        // Handle forms unless user declines service
        if ( ! isset( $route['handle_forms']) || $route['handle_forms'])

            $this->data = array_merge( $this->handle_forms($this->post_data),$this->data);

        // Login ?
        if ( ! @$this->data['is_ajax_login'] && isset( $this->data['login_result']) && $this->data['login_result']){

            header('Location: /');

        }


        // Add current user
        $officer = (new Officer());

        $this->data = array_merge( $this->data, array(

            '_is_logged_in' => $officer->is_admitted(),

            '_current_user' => $officer->me()->as_array(),


        ));

        $this->render( (string) new Controller_Key());


    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function is_production_host(){

        return (new Is_Production_Host())->bool_value();

    }

}