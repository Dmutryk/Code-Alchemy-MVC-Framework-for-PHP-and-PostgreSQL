<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/29/15
 * Time: 7:51 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Components;


use Code_Alchemy\Core\Canonical_Classname;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Models\Model_Companion;
use Code_Alchemy\Payment_Alchemy\Payment_Alchemy;

/**
 * Class Payment_Component
 * @package Code_Alchemy\Payment_Alchemy\Components
 *
 * A Payment component is an object used to manage payments
 */
abstract class Payment_Component extends Model_Companion {

    /**
     * Payment_Component constructor.
     * @param array $seed_values
     */
    public function __construct( array $seed_values = array()){

        parent::__construct( $seed_values );

    }

    /**
     * Persists values, .e.g configuration
     */
    public function _persist(){



    }

    /**
     * @return int new database Id for created Component
     */
    public function create(){

        $this->model = (new Model($this->model_name ))->create_from( $this->seed_values);

    }

    /**
     * @return int new database Id for created Component
     */
    public function save(){

        return $this->put();

    }

    /**
     * @param $id
     * @return $this
     */
    public function fetch_by_id( $id ){

        $this->find(new Key_Column((string) new Canonical_Classname(get_called_class()))."='$id'");

        return $this;

    }

    /**
     * @return array of members for component
     */
    public function get_members(){

        return $this->as_array();

    }

}