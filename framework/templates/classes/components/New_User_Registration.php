<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/7/15
 * Time: 9:19 PM
 */

namespace __namespace__\Components;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Security\Officer;

/**
 * Class New_User_Registration
 * @package classpoc\Components
 *
 * New User Registration
 */
class New_User_Registration extends Array_Representable_Object{

    /**
     * New_User_Registration constructor.
     * @param array $seeds to create User
     */
    public function __construct( array $seeds ){

        $new_user = (new Model('user'))

            ->create_from(array_merge($seeds,array(

                //'profile_id' => (new Model('profile'))->find("seo_name='associate'")->id()

            )));

        $this->array_values = $new_user->as_array();

        // If unsuccessful
        if ( ! $new_user->exists ){

            $this->result = 'error';

            $this->error = $new_user->error();

            $this->missing_fields = $new_user->missing_fields();

        } else {

            // Log in first time as a courtesy

            $officer = (new Officer());

            $this->is_logged_in = !! $officer

                ->auto_login_as( $new_user);

            $this->auto_login_error = $officer->last_error;

        }

    }

}