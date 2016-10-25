<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 10/31/14
 * Time: 12:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\Multimedia\Video;

use Code_Alchemy\Multimedia\Multimedia_Component;

class VimeoVideo extends Multimedia_Component{

    /**
     * @var string Unique Vimeo Video Id
     */
    private $vimeo_video_id = '';

    /**
     * @var array of data fetched about video
     */
    private $data = array();

    /**
     * @param string $media_url such as copied/pasted from site
     */
    public function __construct( $media_url ){

        // Stash
        $this->vimeo_video_id = $this->parse_media_url( $media_url );

        //$hash = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/$this->vimeo_video_id.php"));

        //if ( is_array( $hash )) $this->data = $hash[0];

    }

    /**
     * @param $media_url
     * @return string Vimeo Video Id
     */
    public function parse_media_url( $media_url ){

        $video_id = '';

        if ( preg_match('/https:\/\/vimeo\.com\/([0-9]+)/',$media_url,$hits))

            $video_id = $hits[1];

        return $video_id;
    }

    /**
     * @return array of thumbnails
     */
    public function thumbnails(){

        $thumbs = array();

        foreach ( $this->data as $member=>$value )

            if ( preg_match( '/thumbnail/',$member))

                $thumbs[$member] = $value;

        return $thumbs;

    }

    /**
     * @return string Vimeo Video Id
     */
    public function __toString(){

        return $this->vimeo_video_id;

    }


}