<?php


namespace Code_Alchemy\managers;


use Code_Alchemy\helpers\messenger;
use Code_Alchemy\helpers\model_class_for;
use Code_Alchemy\models\model_wrapper;
use Code_Alchemy\parnassus;

class Relationship_Manager {

    /**
     * @var \SimpleXMLElement settings
     */
    private $settings;

    /**
     * Settings are in the XML file
     */
    public function __construct(){

        $this->settings = parnassus::instance()->configuration()->Relationship_Manager;

    }

    /**
     * Invite users back, who haven't logged in in a while
     * @param string $threshold to check back
     * @param string $template_key for sending message
     * @param array $custom_data
     * @return int number of users invited back
     */
    public function invite_users_back(
        $threshold = '2 weeks',
        $template_key = 'invite-back',
        array $custom_data = array()
    ){

        $num_invited = 0;

        $threshold_date = date('Y-m-d H:i:s',strtotime("-$threshold"));

        $user_class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For('user');

        if ( $user_class ){

            $wrapper = new model_wrapper( $user_class::model() );

            foreach( $wrapper->model()->find_all_undeleted("LIMIT 1,is_invited_back='0',last_login_date<'$threshold_date'") as $user ){

                $custom_data['logged_in_ago'] = (string) new \human_time(strtotime($user->last_login_date));

                //echo "User ".$user->seo_name()." hasn't logged in for more than $threshold\r\n";
                $messenger = new messenger($template_key,array_merge($custom_data,$user->as_array()),'template_key');

                if ( $messenger->send_to($user->email,$custom_data['help_email'],$custom_data['organization_name'],$custom_data['cc_email'],$custom_data['http_host']))

                {

                    $user->is_invited_back = true;

                    $user->save();

                    $num_invited++;
                } else {

                    echo $messenger->error."\r\n";
                }

            }
        }


        return $num_invited;

    }

}