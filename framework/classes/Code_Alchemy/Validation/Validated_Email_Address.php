<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/29/16
 * Time: 1:49 PM
 */

namespace Code_Alchemy\Validation;
use Code_Alchemy\Models\Model;

/**
 * Class Validated_Email_Address
 * @package Code_Alchemy\Validation
 *
 * Validated email address
 */
class Validated_Email_Address extends Validated_Data{

    /**
     * Validate email
     * @param array $data
     */
    public function validate(array $data) {

        $email = isset($data['email']) ? $data['email']: 'not_an_email';

        $this->_is_valid = ($this->is_email_address( $email ) && ! $this->user_exists( $email ));

    }

    /**
     * @param $email
     * @return bool
     */
    private function is_email_address( $email ){

        $filter_var = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ( ! $filter_var )

            $this->_errors[] = "Not a valid email address";

        return !!$filter_var;

    }

    /**
     * @param $email
     * @return bool
     */
    private function user_exists( $email ){

        $model = (new Model('user'));

        $exists = $model->find("email='$email'")->exists;

        if ( $exists ) $this->_errors[] = $model->error();

        return $exists;
    }
}