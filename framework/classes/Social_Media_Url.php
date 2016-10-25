<?php


namespace Code_Alchemy\helpers;

/**
 * Class Social_Media_Url represents a Social Media URL
 * @package parnassus\helpers
 */
class Social_Media_Url {

    /**
     * @var array of permitted social media types
     */
    protected $scope = array(
        'facebook','twitter','linkedin','youtube','vimeo','soundcloud'
    );

    /**
     * @var string Url of Social Media
     */
    private $url = '';

    /**
     * @var string The type of media, if known
     */
    private $type = 'unknown';

    /**
     * @param $url
     * @param array $scope
     */
    public function __construct( $url, $scope = array() ){

        // Can override scope
        if ( count($scope) ) $this->scope = $scope;

        $this->url = $url;

    }

    /**
     * @return bool true if URL is valid
     */
    public function is_valid(){

        $result = false;

        // regex for each scope
        $scoped_regex = array(
            'soundcloud'=>'~^(?:https?://)?(?:www\.)?(?:soundcloud\.com)/([a-zA-Z0-9_\.-]+)/([a-zA-Z0-9_\.-]+)~x',
            'youtube'=>'~^(?:https?://)?(?:www\.)?(?:youtube\.com|youtu\.be)/watch\?v=([^&]+)~x',
            'facebook'=>'~^(?:https?://)?(?:www\.)?(?:facebook\.com)/([a-zA-Z0-9_\.])+~x',
            'linkedin'=>'~^(?:https?://)?(?:www\.)?(?:linkedin\.com)/([a-zA-Z0-9_\.])+~x',
            'twitter'=>'~^(?:https?://)?(?:www\.)?(?:twitter\.com)/([a-zA-Z0-9_\.])+~x',
            'vimeo'=>'~^(?:https?://)?(?:www\.)?(?:vimeo\.com)/([a-zA-Z0-9_\.])+~x',
        );

        foreach ( $this->scope as $type )

            if ( preg_match($scoped_regex[$type],$this->url)){

                $result = true;

                $this->type = $type;

            }

        return $result;

    }

    /**
     * @return string type of media, if known
     */
    public function type(){

        return $this->type;

    }

    /**
     * @return array representation
     */
    public function as_array(){

        return array(
            'scope'=>$this->scope,
            'url'=>$this->url,
            'is_valid'=>$this->is_valid(),
            'type'=>$this->type(),
        );
    }
}