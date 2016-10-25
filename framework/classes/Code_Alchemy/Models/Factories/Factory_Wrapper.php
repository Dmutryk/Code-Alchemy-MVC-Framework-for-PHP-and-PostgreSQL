<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 1/10/15
 * Time: 2:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Models\Factories;


class Factory_Wrapper {

    /**
     * @var \xo_model to wrap
     */
    private $model;

    /**
     * @var bool true to send output to firebug
     */
    private $firebug = false;

    /**
     * @param Model_Factory $model
     * @param bool $firebug
     */
    public function __construct( Model_Factory $model, $firebug = false ){

        $this->firebug = $firebug;

        $this->model = $model;

        if ( $this->firebug ) \FB::info(get_class($model));

    }

    /**
     * @return Model_Factory the wrapped model
     */
    public function model(){ return $this->model; }

}