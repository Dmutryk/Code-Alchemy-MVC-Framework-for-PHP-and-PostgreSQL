<?php
/**
 * This is the Controller parent class, extended from X-Objects.
 * You can put application-wide Controller settings here
 */

namespace _mynamespace_\controllers;
use _mynamespace_\components\state;

use Code_Alchemy\mvc\controllers\Application_Controller;
use xobjects;


class app_controller  extends Application_Controller {

    /**
     * @var null Data for Controller
     */
    protected $data = null;

    /**
     * @return state for application
     */
    public function state(){

        return new state( $this->uri()->part(1) );

    }

    /**
     * @return null Data associated with Controller
     */
    public function data(){

        return $this->data;

    }

}