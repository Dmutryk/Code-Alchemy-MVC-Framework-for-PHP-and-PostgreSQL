<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/12/15
 * Time: 4:55 PM
 */

namespace Code_Alchemy\Multimedia\Video;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Youtube_Video_Thumbnail
 * @package Code_Alchemy\Multimedia\Video
 *
 * Get a Youtube Video thumbnail from Youtube's API
 */
class Youtube_Video_Thumbnail extends Stringable_Object {

    /**
     * @param string $unique_youtube_identity for video to get thumbnail
     */
    public function __construct( $unique_youtube_identity ){

        $this->string_representation = "http://img.youtube.com/vi/$unique_youtube_identity/1.jpg";

    }

}