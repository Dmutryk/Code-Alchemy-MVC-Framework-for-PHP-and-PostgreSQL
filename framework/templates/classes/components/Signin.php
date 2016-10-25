<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/8/15
 * Time: 11:18 PM
 */

namespace classpoc\Components;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Security\Officer;

/**
 * Class Signin
 * @package classpoc\Components
 *
 * Perform a Signin
 */
class Signin extends Array_Representable_Object{

    /**
     * Signin constructor.
     * @param array $signin_data
     */
    public function __construct( array $signin_data ){

        $officer = (new Officer());

        $this->result = $officer

            ->login_using($signin_data['email'],$signin_data['password'],$signin_data['remember_me']);

        $this->error = $officer->last_error;

    }

}