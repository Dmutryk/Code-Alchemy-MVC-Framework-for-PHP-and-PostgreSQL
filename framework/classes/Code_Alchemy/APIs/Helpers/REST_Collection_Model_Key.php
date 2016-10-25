<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/18/15
 * Time: 11:44 AM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Models\Model;

/**
 * Class REST_Collection_Model_Key
 * @package Code_Alchemy\APIs\Helpers
 *
 * Model key for a Model in a REST collection of models
 */
class REST_Collection_Model_Key extends Alchemist{

    /**
     * @var int|null
     */
    private $key = null;

    /**
     * REST_Collection_Model_Key constructor.
     * @param Model $model
     */
    public function __construct( $model ){

        $this->key = isset($_REQUEST['_is_associative']) && $_REQUEST['_is_associative']

            ? $model->id() : null;

    }

    /**
     * @return int|null
     */
    public function key(){ return $this->key; }

}