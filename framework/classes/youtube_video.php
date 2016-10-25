<?php
/**
 * Object representation of a Youtube Video
 */
namespace Code_Alchemy;


class youtube_video {

    /**
     * @var string Youtube Id
     */
    private $youtube_id = '';

    /**
     * @param string $link
     */
    public function __construct( $link ){

        // fetch Youtube Id
        $this->youtube_id = $this->get_youtube_id( $link );

    }

    /**
     * @param string $link from Youtube
     * @return string id of video
     */
    private function get_youtube_id( $link ){

        $id = '';

        // scenario 1
        if ( preg_match( '/https\:\/\/www\.youtube\.com\/watch\?v\=([a-zA-Z0-9\-\_]+)/',$link,$hits))

            $id = $hits[1];

        return $id;

    }

    /**
     * @return string representation, as youtube id
     */
    public function __toString(){

        return $this->youtube_id;
    }
}