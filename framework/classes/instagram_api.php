<?php
/**
 * Project:
 * Module:
 * Component:
 * Description:
 * Author:
 * Copyright:
 */

namespace Code_Alchemy;

class instagram_api extends api_service implements api_specification {

    /**
     * @var instagram handle to Instagram instance
     */
    private $instagram = null;

    /**
     * @var string screen name of user for which results are being fetched
     */
    private $screenName = '';

    /**
     * @param array $options to set when instantiating the API
     */
    public function __construct($options){

        $this->options['method_index'] = self::default_method_index;

        parent::__construct($options);

        // create API core component
        $this->instagram  = new instagram(array(
            'apiKey'=>$options['apiKey'],
            'apiSecret'=>$options['apiSecret'],
            'apiCallback'=>$options['apiCallback']
        ));

        // if already authenticated, pass token
        if ( isset( $options['access_token']) && $options['access_token'])
            $this->instagram->setAccessToken($options['access_token']);

        // if option set, create a simple disk cache
        if ( isset( $options['disk_cache_model'])){

            $this->disk_cache = new disk_cache(array(
                'model_name'=>$options['disk_cache_model'])
            );

        }

    }

    /**
     * Process the Instagram APi request
     * @return api_result|instagram_result
     */
    public function process_request(){

       $result = new instagram_result();

       $this->screenName = $this->uri->part( $this->options['method_index']+1);


        /* if ( ! isset($this->options['access_token']) ){

             $this->error = "User is either not logged in, or not authorized to Instagram API";

             $this->result = false;

         } else {*/
        $method = $this->uri->part((int)$this->options['method_index']);

        if ( ! $method ){

            $this->error = "No API method specified in position ".$this->options['method_index'] . " of URL";

            $this->result = false;

        } else {

            if ( ! method_exists($this,$method)) {

                $this->error = "$method: No such recognized API method";

                $this->result = false;

            } else {

                $this->$method( $result, $this->instagram );

            }

        }
        //}
        $result->result = $this->result?'success':'error';

        $result->error = $this->error;

        return $result;

    }

    /**
     * @return string the login url for this service
     */
    public function get_login_url(){

        return $this->instagram->getLoginUrl();

    }

    /**
     * Translate an Instagram screen name into a user id
     * @param $screen_name
     * @return mixed
     */
    public function translate_screenname( $screen_name ){

        return $this->cached_result("transl_instg_scrnm.".$screen_name);

    }

    /**
     * Translate an Instagram screen name into a UserID
     * @param api_result $result
     * @param instagram $instagram
     */
    private function userid( &$result, $instagram ){

        $result->instagram_id = (string) $this->cached_result("transl_instg_scrnm.".$this->screenName);

        if ( ! $result->instagram_id ){

            $this->result = false;

            $this->error = "$this->screenName: No such Instagram Screen Name found";

        }

    }

    /**
     * Get the login URL for this Instagram App
     * @param api_result $result
     * @param instagram $instagram
     */
    private function login_url( &$result, $instagram ){

        $result->login_url = $this->instagram->getLoginUrl();

        $result->method = __FUNCTION__;


    }

    /**
     * @param $result object, to set any results
     * @param instagram $instagram
     *     */
    private function bundle( &$result, $instagram ){

        $debug = false;

        global $container;

        $tag = new \xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        $screenName = $this->screenName;

        if ( ! $screenName ){

            $this->result = false;

            $this->error = "No Instagram Screen Name specified in position ".($this->options['method_index']+1);

        } else {

            $result->screen_name = $screenName;

            /**
             * Get several different results, such as media and follows
             */
            $result->media = $this->cached_result('instagram_media.'.$screenName);
            $result->follows = $this->cached_result('instagram_follows.'.$screenName);
            $result->followers = $this->cached_result('instagram_followers.'.$screenName);

        }

        $result->method = __FUNCTION__;

        $result->cache_hits = $this->num_cache_hits;
        $result->api_calls = $this->num_api_calls;

    }


    /**
     * Get a specific API result based on a lookup key
     * @param $key
     * @param string|null $screenName
     * @return int|null
     */
    public  function get_api_result( $key , $screenName = null){

        $result = null;

        $screenName = $screenName?$screenName:$this->screenName;


        switch( @array_shift(explode('.',$key) )){

            /**
             * Translate the Instagram Screen Name into a UserID
             */
            case 'transl_instg_scrnm':

                $user = $this->instagram->searchUser($screenName);
                $result = (string) $user->data[0]->id;

                $this->num_api_calls++;

                break;

            case 'instagram_followers':

                $userFollows = $this->instagram->getUserFollower($screenName);

                $result = $userFollows->data;

                $this->num_api_calls++;

            break;


            case 'instagram_follows':

                $userFollows = $this->instagram->getUserFollows($screenName);

                $result = $userFollows->data;

                $this->num_api_calls++;

            break;

            /**
             * Get the most recent media published by the user
             */
            case 'instagram_media':

                $userMedia = $this->instagram->getUserMedia($screenName);

                $result = $userMedia->data;

                    $this->num_api_calls++;

            break;


            /**
             * Get the number of media the user has published
             */
            case 'publishes':

                $userMedia = $this->instagram->getUserMedia($screenName);

                if ( is_array( $userMedia) && isset( $userMedia['result']) && $userMedia['result']==='error' ){

                    $this->result = false;

                    $this->error = 'You must present an authorization token to make API calls';

                }

                $result = count($userMedia->data);

                $this->num_api_calls++;

            break;

        }

        return $result;

    }

}