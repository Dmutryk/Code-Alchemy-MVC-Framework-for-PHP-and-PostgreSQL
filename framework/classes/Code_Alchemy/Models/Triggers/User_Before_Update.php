<?php


namespace Code_Alchemy\Models\Triggers;


use Code_Alchemy\Security\Officer;

/**
 * Class User_Before_Update
 * @package Code_Alchemy\Models\Triggers
 *
 * Trigger to fire before updating a User
 */
class User_Before_Update extends Model_Trigger {

    public function __construct( array $values, array $members ){

        // New password?
        if ( isset( $values['password']))

            // Hash it!
            $values['password'] = (new Officer())->password_hash($values['password'],$members['salt']);

        $this->array_values = $values;

    }

}