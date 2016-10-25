<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/8/15
 * Time: 4:41 PM
 */

namespace Code_Alchemy\Models;


use Code_Alchemy\Core\Canonical_Classname;

/**
 * Class Model_Companion
 * @package Code_Alchemy\Models
 *
 * A Model Companion is a convenient way to instantiate a Model by name
 * as an encapsulated class
 */
abstract class Model_Companion extends Model {

    /**
     * Model_Companion constructor.
     * @param array $seed_values for new Model
     */
    public function __construct( array $seed_values = array() ){

        parent::__construct(

            strtolower((string) new Canonical_Classname(get_called_class())),

            $seed_values

        );

    }

}