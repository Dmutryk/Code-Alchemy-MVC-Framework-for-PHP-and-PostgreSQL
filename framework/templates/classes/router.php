<?php

/**
 *
 * Custom Router for the application; Automagically detected by X-Objects
 * and all route requests are pre-filtered through it.
 *
 * User: "David Owen Greenberg" <owen.david.us@gmail.com>
 * Date: 13/03/13
 * Time: 11:30 AM
 */

class _appname__router extends xo_router {

    public function __construct($key = null){

        parent::__construct($key);

    }

    /**
     * Return a route based on a key
     * @param null $key
     * @return string
     */
    public function route($key = null){

        $route = null;

        // get key as a parm, or set by parent class
        $key = $key?$key:$this->key;

        // take action based on key
        switch( $key ){

            // Special case for Web Director
            case 'web-director':

                $route = 'web_director';

            break;

            default:

               $route = 'main';

            break;
        }

        return $route;
    }

    /**
     * Return the controller file for a route
     * @param null $key
     * @return string
     */
    public function controller_file($key=null){

        $file = 'home';

        $key = $key?$key:$this->key;

        switch( $key ){

            case 'web-director':

                $file = 'web_director';

            break;

            default:

                $file = "main";

            break;

        }

        return $file;

    }

    /**
     * @param null $key
     * @return mixed|null|string
     */
    public function controller_method($key=null){

        $method = 'default_action';

        $uri = new \Code_Alchemy\Core\REQUEST_URI();

        if ( $uri->part(2) )

            $method = $uri->part(2);

        return $method;
    }
}