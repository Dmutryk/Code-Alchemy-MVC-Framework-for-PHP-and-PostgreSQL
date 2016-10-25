<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/10/15
 * Time: 6:22 PM
 */

namespace Code_Alchemy\Email;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Model;

/**
 * Class Save_n_Send
 * @package Code_Alchemy\Email
 *
 * Allows you to Save n Send a Guest Message at once
 */
class Save_n_Send extends Array_Representable_Object{

    /**
     * Save n Send
     * @param string $model_name
     * @param array $data
     * @param array $send_parameters
     */
    public function __construct( $model_name, array $data, array $send_parameters ){

        $model = (new Model( $model_name))

            ->create_from( $data );

        if ( ! $model || ! $model->exists ){

            $this->result = 'error';

            $this->error = $model->error();

            return;

        }

        $email_Messenger = (new Email_Messenger($send_parameters['template_key'], $model->as_array(), false));

        $email_Messenger

            ->send_to($send_parameters['to'],$send_parameters['from_email'],$send_parameters['from'],
                $send_parameters['cc']);

        $this->result = $email_Messenger->error ? 'error': 'success';

        $this->error = $email_Messenger->error;


    }

}