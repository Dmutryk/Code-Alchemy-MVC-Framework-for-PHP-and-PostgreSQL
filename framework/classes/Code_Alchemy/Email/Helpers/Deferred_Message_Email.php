<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/12/15
 * Time: 1:51 PM
 */

namespace Code_Alchemy\Email\Helpers;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Models\Interfaces\Model_Interface;
use Code_Alchemy\Models\Model;

/**
 * Class Deferred_Message_Email
 * @package Code_Alchemy\Email\Helpers
 *
 * Email for a deferred message
 */
class Deferred_Message_Email extends Stringable_Object{

    /**
     * Deferred_Message_Email constructor.
     * @param Model $deferred
     */
    public function __construct( Model_Interface $deferred ){

        $this->string_representation = $deferred->email ?

            $deferred->email : (new Model('user'))

            ->find("id='".$deferred->user_id."'")->email;

    }
}