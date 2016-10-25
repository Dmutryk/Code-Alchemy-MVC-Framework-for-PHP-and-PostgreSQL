<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/8/16
 * Time: 11:05 AM
 */

namespace Code_Alchemy\Models;


use Code_Alchemy\Core\Alchemist;

class Model_Fetcher extends Alchemist{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @return Model
     */
    public function model(){ return $this->model; }

}