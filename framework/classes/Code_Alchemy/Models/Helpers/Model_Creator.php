<?php


namespace Code_Alchemy\Models\Helpers;


class Model_Creator {

    /**
     * @var int User Id who created the Model
     */
    private $user_id = 0;

    /**
     * @var array of cached creators
     */
    private static $cache = array();

    /**
     * @param int $user_id
     */
    public function __construct( $user_id ){

        $this->user_id = $user_id;

        // Add to cache if necessary
        if ( ! isset( self::$cache[ $user_id ])){

            $user_class = (string) new Model_Class_For('user');

            self::$cache[ $user_id ] = new $user_class("id='$user_id'");

        }


    }

    /**
     * @return Generic_Model
     */
    private function user(){

        $id = $this->user_id;

        $user = null;

        if ( isset( self::$cache[ $id ])){

            $user = new Generic_Model( self::$cache[ $id ] );

        }

        return $user;
    }


    public function reference_column(){

        $column = '';

        $user = $this->user();

        if ( $user ) $column = $user->model()->reference_column();

        return $column;

    }

    /**
     * @return string Reference Name
     */
    public function reference_name(){

        $name = '';

        $user = $this->user();

        if ( $user ) $name = $user->model()->reference_value();

        return $name;

    }

}