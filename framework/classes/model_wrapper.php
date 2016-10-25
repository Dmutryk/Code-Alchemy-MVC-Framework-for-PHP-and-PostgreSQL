<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 1/10/15
 * Time: 2:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\models;


use Code_Alchemy\Models\Factories\Model_Factory;

class model_wrapper {

    /**
     * @var \xo_model to wrap
     */
    private $model;

    /**
     * @var bool true to send output to firebug
     */
    private $firebug = false;

    /**
     * @param \xo_model $model
     * @param bool $firebug
     */
    public function __construct( Model_Factory $model, $firebug = false ){

        $this->firebug = $firebug;

        $this->model = $model;

        if ( $this->firebug ) \FB::info(get_class($model));

    }

    /**
     * @return \xo_model the wrapped model
     */
    public function model(){ return $this->model; }

}