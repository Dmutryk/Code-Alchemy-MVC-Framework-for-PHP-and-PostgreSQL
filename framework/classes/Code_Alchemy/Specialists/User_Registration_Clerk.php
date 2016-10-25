<?php


namespace Code_Alchemy\Specialists;


use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Security\Officer;
use Code_Alchemy\helpers\model_class_for;
use Code_Alchemy\models\model_wrapper;

class User_Registration_Clerk extends Specialized_Component{

    /**
     * @var array of data for registration
     */
    private $data = array(

    );

    /**
     * @var bool true to debug
     */
    private $firebug = true;



    /**
     * @param array $data for signup
     */
    public function __construct( array $data ){

        $this->data = $data;
    }

    /**
     * Perform duties
     * @param bool $verbose output
     */
    public function perform_duties( $verbose = false , $dont_redirect = false){

        $result = true;

        $error = '';

        $user_id = 0;

        $model = (new Model('user'));

        $user = $model->create_from($this->data);

        $result = $user->exists;

        $user_id = $user->id;

        $error = $model->error();

        $this->error = $error;

        $this->result_data = array(
            'signup_result'=>$result,
            'signup_error'=>$error,
            'user_id'=>$user_id,
            'is_auto_logged' => false,
            'aut_login_error' => ''
        );

        if ( $result ){

            // Get configuration
            $config = (new Configuration_File())->find('signup');

            // If auto login
            if ( $config['auto-login']){

                $officer = new Officer();

                $this->result_data['is_auto_logged'] = $officer->auto_login_as(

                    (new Model('user'))->find(new Key_Column('user')."='$user_id'")

                );

                $this->result_data['aut_login_error'] = $officer->last_error;

                if ( ! $dont_redirect && $config['redirect'])

                    header('Location: '.$config['redirect']);

            }



        } else {

            return false;
        }

        return true;

    }

}