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
use TijsVerkoyen\Twitter;


class twitter_api extends api_service implements api_specification {

    /**
     * @var null|\TijsVerkoyen\Twitter\Twitter handle to twitter instance
     */
    private $twitter = null;

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

        $this->twitter = new Twitter\Twitter(
            $options['consumer_key'],
            $options['consumer_secret']
        );

        $this->twitter->setOAuthToken($options['oauth_token']);
        $this->twitter->setOAuthTokenSecret($options['oauth_token_secret']);

        // if option set, create a simple disk cache
        if ( isset( $options['disk_cache_model'])){

            $this->disk_cache = new disk_cache(array(
                'model_name'=>$options['disk_cache_model'])
            );

        }

    }

    public function process_request(){

        $result = new twitter_result();

        if ( ! isset($this->options['oauth_token']) && ! isset($this->options['oauth_token_secret'])){

            $this->error = "User is either not logged in, or not authorized to Twitter API";

            $this->result = false;

        } else {
            $method = $this->uri->part((int)$this->options['method_index']);

            if ( ! $method ){

                $this->error = "No API method specified in position ".$this->options['method_index'] . " of URL";

                $this->result = false;

            } else {

                if ( ! method_exists($this,$method)) {

                    $this->error = "$method: No such recognized API method";

                    $this->result = false;

                } else {

                    $this->$method( $result, $this->twitter );

                }

            }


        }


        $result->result = $this->result?'success':'error';

        $result->error = $this->error;

        return $result;

    }

    /**
     * Get the Avatar for a user
     * @param string $screen_name
     * @return mixed
     */
    public function avatar_url( $screen_name ){

        $this->screenName = $screen_name;
        return $this->cached_result("twitter_avatar.".$screen_name);

    }


    private function avatar( &$result, $twitter ){
        $this->screenName = $screenName = $this->uri->part( $this->options['method_index']+1);

        if ( ! $screenName ){

            $this->result = false;

            $this->error = "No Twitter Screen Name specified in position ".($this->options['method_index']+1);

        } else {

            $result->avatar = $this->cached_result('twitter_avatar.'.$screenName);

        }

        $result->method = __FUNCTION__;

        $result->cache_hits = $this->num_cache_hits;
        $result->api_calls = $this->num_api_calls;


    }

    /**
     * @param $result object, to set any results
     * @param Twitter\Twitter $twitter
     *     */
    private function bundle( &$result, $twitter ){

        $this->screenName = $screenName = $this->uri->part( $this->options['method_index']+1);

        if ( ! $screenName ){

            $this->result = false;

            $this->error = "No Twitter Screen Name specified in position ".($this->options['method_index']+1);

        } else {

            $result->screen_name = $screenName;

            $result->time_period = 'Last 24 hours';

            /**
             * Get various API resources and set them in the result
             */
            $result->tweets = $this->cached_result('twitter_tweets.'.$screenName);
            $result->follows = $this->cached_result("twitter_follows.$screenName");

        }

        $result->method = __FUNCTION__;

        $result->cache_hits = $this->num_cache_hits;
        $result->api_calls = $this->num_api_calls;

    }

    /**
     * Get a specific API result based on a lookup key
     * @param string $key
     * @param null|string $screenName
     * @return int|null
     */
    public function get_api_result(
        $key,
        $screenName = null,
        $write_cache = false,
        $debug = false
){


        $tag = new \xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        if ( $debug) echo "$tag->event_format: Getting API result for $key, $screenName<br>\r\n";

        $result = null;

        $orig_key = $key.".". ($screenName? $screenName:$this->screenName);

        switch( @array_shift(explode('.',$key) )){

            case 'twitter_avatar':

                $data = $this->twitter->usersLookup(null,$screenName?$screenName:$this->screenName,false);

                $result = $data[0]['profile_image_url'];

                $this->num_api_calls++;

            break;

            case 'twitter_follows':

                $data = $this->twitter->friendsList(null,$screenName?$screenName:$this->screenName);

                $result = $data['users'];

                $this->num_api_calls++;

            break;

            case 'twitter_tweets':

                $result = $this->twitter->statusesUserTimeline(null,$screenName?$screenName:$this->screenName,null,20);

                //if ( $debug)
                   // echo "$tag->event_format: result = $result<br>\r\n";

                $this->num_api_calls++;

            break;

        }

        // if requested, write to cache
        if ( $write_cache && $this->disk_cache )
            $this->disk_cache->$orig_key = $result;

        return $result;

    }

}