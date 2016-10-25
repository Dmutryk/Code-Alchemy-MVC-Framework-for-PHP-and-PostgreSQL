<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/15/15
 * Time: 8:21 PM
 */

namespace Code_Alchemy\Security;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;

/**
 * Class Change_User_Password
 * @package Code_Alchemy\Security
 *
 * Perfect to show as JSON output, this component changes the user's
 * password, based on validating existing password and other rules
 *
 */
class Change_User_Password extends Array_Representable_Object{

    const ERR_INCORRECT_CURRENT_PASSWORD = 1;

    const ERR_PASSWORDS_DO_NOT_MATCH = 2;

    const ERR_PASSWORD_TOO_SHORT = 3;

    const ERR_SAVING_PASSWORD = 4;

    /**
     * @param $current_password
     * @param $password
     * @param $repeat_password
     */
    public function __construct( $current_password, $password, $repeat_password, $ignore_current = false, $user_id = null ){

        $Officer = new Officer();

        $me = $user_id ? (new Model('user'))->find(new Key_Column('user')."='$user_id'"): $Officer->me();

        if (  ! $ignore_current && $me->password != $Officer->password_hash($current_password,$me->salt) ){

            $this->result = 'error';

            $this->error_code = self::ERR_INCORRECT_CURRENT_PASSWORD;

        }

        elseif ( $password !== $repeat_password ){

            $this->result = 'error';

            $this->error_code = self::ERR_PASSWORDS_DO_NOT_MATCH;


        }

        elseif ( strlen( $password) < 6 ){

            $this->result = 'error';

            $this->error_code = self::ERR_PASSWORD_TOO_SHORT;


        } else {

            $this->result = $me->update(array(

                'password' => $password

            ))->put()? 'success': 'error';

            if ( $this->result == 'error')

                $this->error_code = self::ERR_SAVING_PASSWORD;
        }

    }

}