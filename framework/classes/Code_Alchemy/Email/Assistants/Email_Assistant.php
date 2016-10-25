<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/18/15
 * Time: 10:52 AM
 */

namespace Code_Alchemy\Email\Assistants;


use Code_Alchemy\APIs\Mail_Service\Actors\Send_Email_Soon;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Array_Object;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Email\Email_Messenger;
use Code_Alchemy\Email\Handlebars_Email_Messenger;
use Code_Alchemy\Email\Helpers\Notification_Emails;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Model;

/**
 * Class Email_Assistant
 * @package Code_Alchemy\Email\Assistants
 *
 * The purpose of the Email Assistant is to make sending emails for
 * specific types of actions as easy as possible
 */
class Email_Assistant extends Alchemist{

    /**
     * @var bool true to use email web service
     */
    private $use_email_web_service = false;

    /**
     * @var string Web Service Sender
     */
    private $web_service_sender = '';

    /**
     * @var Array_Object settings
     */
    private $settings ;

    /**
     * Email_Assistant constructor.
     * @param array $options
     */
    public function __construct( array $options = [] ){

        foreach ( $options as $name => $value )

            if ( property_exists($this,$name))

                $this->$name = $value;

        // get settings from config file
        $this->settings = new Array_Object((new Configuration_File())->find('messaging'));

    }

    /**
     * Notify both parties of a triggered action, both end user and
     * administrative users, with a template specified for each one
     * @param string $end_user__template
     * @param string $admin_template
     * @param string $user_email
     * @param array $data
     * @param bool $is_handlebars_template
     */
    public function notify_both_parties(

        $end_user__template, $admin_template, $user_email, array $data,

        $is_handlebars_template = false

    ){

        $notification_emails = (new Notification_Emails())->as_array();

        if ( $this->use_email_web_service ){

            // Send end user template
            \FB::info(new Send_Email_Soon($end_user__template,[ $user_email],$data,$this->web_service_sender));

            // Send to admins
            new Send_Email_Soon($admin_template,$notification_emails,$data,$this->web_service_sender);

        } else {

            // First end user
            $from = $this->settings->get('default-from');

            $name = $this->settings->get('default-name');

            (new Email_Messenger($end_user__template,$data))

                ->send_to($user_email, $from,$name);


            (new Email_Messenger($admin_template,$data))

                ->send_to_all($notification_emails,$from,$name);

        }


    }


    /**
     * Defer notify everyone
     * @param $end_user_template_key
     * @param $admin_template_key
     * @param $end_user_email
     * @param $model_name
     * @param $model_id
     */
    public function defer_notify_both_parties( $end_user_template_key, $admin_template_key, $end_user_email, $model_name, $model_id ){

        (new Model('email_message'))

            ->create_from(array(

                'email_template_id' => (new Model('email_template'))->find("template_key='$end_user_template_key'")->id(),

                'email' => $end_user_email,

                'model_name' => $model_name,

                'model_id' => $model_id

            ));

        $id_for_admin_template = (new Model('email_template'))

            ->find("template_key='$admin_template_key'")->id();

        // For each admin
        foreach ((new Notification_Emails())->as_array() as $email ){

            (new Model('email_message'))

                ->create_from(array(

                    'email_template_id' => $id_for_admin_template,

                    'email' => $email,

                    'model_name' => $model_name,

                    'model_id' => $model_id

                ));

        }

    }
}